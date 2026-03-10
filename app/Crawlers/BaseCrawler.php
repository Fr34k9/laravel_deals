<?php

declare(strict_types=1);

namespace App\Crawlers;

use App\Contracts\CrawlerInterface;
use App\Models\Deal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

abstract class BaseCrawler implements CrawlerInterface
{
    public object $config;
    protected bool $debug = false;

    public function __construct(?array $config = null)
    {
        $this->config = (object) ($config ?? []);

        $this->debug = function_exists('app') && app()->resolved('app') && !app()->runningInConsole();
    }

    /**
     * @param string $url
     * @param array<string, string> $headers
     * @return string
     */
    protected function crawl(string $url, array $headers = []): string
    {
        $request = Http::withHeaders($headers)
            ->timeout(30)
            ->connectTimeout(10);

        $proxyUrl = $this->getProxyUrl();
        if ($proxyUrl) {
            $request->withOptions(['proxy' => $proxyUrl]);
        }

        $response = $request->get($url);
        $content = $response->body();

        return Str::between($content, '<body>', '</body>');
    }

    /**
     * @return string|null
     */
    private function getProxyUrl(): ?string
    {
        if (isset($this->config->use_proxy) && !$this->config->use_proxy) {
            return null;
        }

        $proxyUrl = config('services.proxy.url') ?? env('PROXY_URL');
        
        if (!empty($proxyUrl) && $this->debug) {
            dump("Using proxy");
        }

        return !empty($proxyUrl) ? $proxyUrl : null;
    }

    /**
     * @param array<string, array<int, array<string, mixed>>> $urls
     * @return array<string, array<int, array<string, mixed>>>
     */
    protected function prepare_store(array $urls): array
    {
        $res = [];
        foreach ($urls as $url => $deals) {
            foreach ($deals as $deal) {
                $tmp = [];
                if (isset($deal['valid']) && empty($deal['valid'])) {
                    continue;
                }
                if (isset($deal['invalid']) && !empty($deal['invalid'])) {
                    $deal['products_left'] = 0;
                }

                $deal_url = isset($deal['url']) && filter_var($deal['url'], FILTER_VALIDATE_URL) ? $deal['url'] : $url;

                $tmp['platform_id'] = $this->config->id ?? null;
                $tmp['title'] = $this->clean($deal['title'] ?? '');
                $tmp['subtitle'] = $this->clean($deal['subtitle'] ?? '');
                $tmp['price'] = $this->clean_price($deal['price'] ?? '0');
                $tmp['else_price'] = $this->clean_price($deal['else_price'] ?? '0');
                $tmp['products_total'] = $this->clean_product_count_total($deal['products_total'] ?? '100');
                $tmp['products_left'] = $this->clean_product_count_left($deal['products_left'] ?? '100');
                $tmp['image'] = $this->image_prefix() . $this->clean($deal['image'] ?? '');
                $tmp['url'] = $deal_url;
                $tmp['updated_at'] = now();
                $res[$url][] = $tmp;
            }
        }

        return $res;
    }

    public function image_prefix(): string
    {
        return '';
    }

    public function clean_price(string|float|int $string): float
    {
        if (is_float($string) || is_int($string)) {
            return (float) $string;
        }

        $string = str_replace(['&#039;', '\'', 'CHF'], '', $string);
        return (float) filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    private function clean_product_count_total(bool|string|int $string): int
    {
        $count = preg_replace('/[^0-9]/', '', (string) $string) ?: '100';
        return (int) $count;
    }

    private function clean_product_count_left(string|int $string): int
    {
        if ($string === '0' || $string === 0) {
            return 0;
        }

        $count = preg_replace('/[^0-9]/', '', (string) $string) ?: '100';
        return (int) $count;
    }

    public function store(array $dealsByUrl): void
    {
        if ($this->debug) {
            dump("Data before preparing", $dealsByUrl);
        }
        $preparedDealsByUrl = $this->prepare_store($dealsByUrl);
        if ($this->debug) {
            dump("Data before storing", $preparedDealsByUrl);
        }

        foreach ($preparedDealsByUrl as $deals) {
            foreach ($deals as $deal) {
                /** @var Deal|null $existing_deal */
                $existing_deal = Deal::where('title', $deal['title'])
                    ->where('updated_at', '>', now()->subDay())
                    ->where('platform_id', $deal['platform_id'])
                    ->first();

                if ($existing_deal) {
                    if ($this->debug) {
                        dump("Updating deal " . $deal['title']);
                    }
                    $existing_deal->update($deal);
                } else {
                    if ($this->debug) {
                        dump("Creating deal " . $deal['title']);
                    }
                    $new_deal = Deal::create($deal);
                    if ($new_deal) {
                        $this->sendDiscordMessage($new_deal);
                    }
                }

                if ($this->debug) {
                    dump($deal);
                }
            }
        }
    }

    protected function searchRegex(string $string, ?string $regex): string|bool
    {
        if (empty($regex)) {
            return false;
        }

        preg_match($regex, $string, $matches);
        return $matches[1] ?? false;
    }

    protected function clean(string $string): string
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    public function crawlDeals(): array
    {
        $deals = [];
        $urls = $this->config->urls ?? [];
        foreach ($urls as $url) {
            if ($this->debug) {
                dump("Crawling " . $url);
            }
            $headers = $this->config->headers ?? [];
            $body = $this->crawl($url, $headers);

            if ($this->config->multiple_products ?? false) {
                $deals[$url] = $this->crawlMultipleDeals($body);
            } else {
                $deals[$url] = $this->crawlOneDeal($body);
            }
            sleep(1);
        }

        return $deals;
    }

    /**
     * @param string $html
     * @return array<int, array<string, mixed>>
     */
    protected function crawlOneDeal(string $html): array
    {
        $deal = [];
        $regexes = $this->config->regex ?? [];
        foreach ($regexes as $key => $regex) {
            $deal[$key] = $this->searchRegex($html, $regex);
        }

        return [$deal];
    }

    /**
     * @param string $html
     * @return array<int, array<string, mixed>>
     */
    protected function crawlMultipleDeals(string $html): array
    {
        $multipleProductsRegex = $this->config->multiple_products ?? null;
        if (!$multipleProductsRegex) {
            return [];
        }

        preg_match_all($multipleProductsRegex, $html, $matches);

        $deals = [];
        foreach ($matches[1] as $deal_html) {
            $deal = [];
            $regexes = $this->config->regex ?? [];
            foreach ($regexes as $key => $regex) {
                $deal[$key] = $this->searchRegex($deal_html, $regex);
            }
            $deals[] = $deal;
        }

        return $deals;
    }

    private function sendDiscordMessage(Deal $deal): void
    {
        if (empty(env('DISCORD_ALERT_WEBHOOK'))) {
            return;
        }

        if ($this->debug) {
            dump("Sending Discord message for deal " . $deal->title);
        }

        $discordPriceString = "**" . $deal->price . ".-** ";
        if ($deal->products_left > 0 && $deal->else_price > 0) {
            $discordPriceString .= " / ~~" . $deal->else_price . ".-~~";
        }

        $appUrl = config('app.url', "https://deals.fr34k.ch/");
        $discordDescription = $discordPriceString . "\n[Alle Deals](" . $appUrl . ")";

        $discordImage = null;
        if (!empty($deal->image)) {
            $discordImage = [
                'url' => $deal->image
            ];
        }

        DiscordAlert::message("", [
            [
                'title' => $deal->subtitle ?? '',
                'description' => $discordDescription,
                "image" => $discordImage,
                'color' => rand(0, 1) ? '#ef5350' : '#409fff',
                'author' => [
                    'name' => $deal->title ?? '',
                    'url' => $deal->url ?? $appUrl,
                ],
            ]
        ]);
    }
}
