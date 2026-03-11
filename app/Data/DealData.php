<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class DealData extends Data
{
    public function __construct(
        public ?int   $platform_id,
        public string $title,
        public string $subtitle,
        public float  $price,
        public float  $else_price,
        public int    $products_total,
        public int    $products_left,
        public string $image,
        public string $url,
        public bool   $invalid = false,
        #[Hidden]
        public ?bool  $valid = null,
    ) {}
}
