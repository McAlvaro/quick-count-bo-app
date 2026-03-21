<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CandidateType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
