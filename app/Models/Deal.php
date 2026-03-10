<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    /** @use HasFactory<\Database\Factories\DealFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the platform that owns the deal.
     */
    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Scope a query to only include deals currently in stock.
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('products_left', '>', 0);
    }

    /**
     * Scope a query to only include deals updated within the last 5 minutes.
     */
    public function scopeRecentlyUpdated(Builder $query): Builder
    {
        return $query->where('updated_at', '>=', now()->subMinutes(5));
    }

    /**
     * Scope a query to only include visible deals for guest users.
     */
    public function scopeVisible(Builder $query): Builder
    {
        if (auth()->guest()) {
            return $query->where('invalid', false);
        }

        return $query;
    }
}
