<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'last_crawled' => 'datetime',
    ];

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
