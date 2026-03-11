<?php

declare(strict_types=1);

namespace App\Crawlers;

use App\Contracts\CrawlerInterface;
use App\Data\DealData;
use App\Models\Deal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

abstract class BaseCrawler implements CrawlerInterface
{
    public object $config;
    protected bool $debug = false;
    protected int $retries = 1;

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
     * Prepare crawled deals into DealData objects, applying cleaning and validation.
     * Deals that are already DealData instances are passed through with platform_id set.
     * Raw arrays from regex-based crawlers go through the full cleaning/conversion path.
     *
     * @param array<string, list<DealData|array<string, mixed>>> $urls
     * @return array<string, DealData[]>
     */
    protected function prepare_store(array $urls): array
    {
        $res = [];
        foreach ($urls as $url => $deals) {
            foreach ($deals as $deal) {
                // Already a DealData instance (from child crawlers with custom parsing)
                if ($deal instanceof DealData) {
                    $res[$url][] = $deal;
                    continue;
                }

                if (isset($deal['valid']) && empty($deal['valid'])) {
                    continue;
                }

                $productsLeft = $this->clean_product_count_left($deal['products_left'] ?? '100');
                if (isset($deal['invalid']) && !empty($deal['invalid'])) {
                    $productsLeft = 0;
                }

                $deal_url = isset($deal['url']) && filter_var($deal['url'], FILTER_VALIDATE_URL) ? $deal['url'] : $url;

                $res[$url][] = new DealData(
                    platform_id: $this->config->id ?? null,
                    title: $this->clean($deal['title'] ?? ''),
                    subtitle: $this->clean($deal['subtitle'] ?? ''),
                    price: $this->clean_price($deal['price'] ?? '0'),
                    else_price: $this->clean_price($deal['else_price'] ?? '0'),
                    products_total: $this->clean_product_count_total($deal['products_total'] ?? '100'),
                    products_left: $productsLeft,
                    image: $this->image_prefix() . $this->clean($deal['image'] ?? ''),
                    url: $deal_url,
                );
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

    private function clean_product_count_left(bool|string|int $string): int
    {
        if ($string === '0' || $string === 0) {
            return 0;
        }

        $count = preg_replace('/[^0-9]/', '', (string) $string) ?: '100';
        return (int) $count;
    }

    /**
     * @param array<string, DealData[]> $dealsByUrl
     */
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
            foreach ($deals as $dealData) {
                $attributes = $dealData->toArray();
                $attributes['updated_at'] = now();

                /** @var Deal|null $existing_deal */
                $existing_deal = Deal::where('title', $dealData->title)
                    ->where('updated_at', '>', now()->subDay())
                    ->where('platform_id', $dealData->platform_id)
                    ->first();

                if ($existing_deal) {
                    if ($this->debug) {
                        dump("Updating deal " . $dealData->title);
                    }
                    $existing_deal->update($attributes);
                } else {
                    if ($this->debug) {
                        dump("Creating deal " . $dealData->title);
                    }
                    $new_deal = Deal::create($attributes);
                    if ($new_deal) {
                        $this->sendDiscordMessage($new_deal);
                    }
                }

                if ($this->debug) {
                    dump($dealData);
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

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function crawlDeals(): array
    {
        $deals = [];
        $urls = $this->config->urls ?? [];
        foreach ($urls as $url) {
            if ($this->debug) {
                dump("Crawling " . $url);
            }
            $headers = $this->config->headers ?? [];

            $body = null;
            for ($attempt = 0; $attempt <= $this->retries; $attempt++) {
                try {
                    $body = $this->crawl($url, $headers);
                    break;
                } catch (\Exception $e) {
                    if ($attempt < $this->retries) {
                        Log::warning("Crawl attempt " . ($attempt + 1) . " failed for {$url}: " . $e->getMessage() . ". Retrying...");
                        sleep(2);
                    } else {
                        Log::error("Crawl failed after " . ($attempt + 1) . " attempts for {$url}: " . $e->getMessage());
                    }
                }
            }

            if ($body === null) {
                continue;
            }

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
