<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Platform extends Model
{
    /** @use HasFactory<\Database\Factories\PlatformFactory> */
    use HasFactory;

    protected $fillable = [
        'active',
        'name',
    ];

    protected $casts = [
        'active' => 'boolean',
        'last_crawled' => 'datetime',
    ];

    /**
     * Get the deals for the platform.
     */
    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    /**
     * Scope a query to only include active platforms.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
