<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Precinct extends Model
{
    protected $guarded = [];

    /**
     * @return HasMany<Table,Precinct>
     */
    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }
}
