<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

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

    /**
     * @return BelongsToMany<User,Precinct,Pivot>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
