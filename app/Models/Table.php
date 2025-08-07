<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    /**
     * @return BelongsTo<Precinct,Table>
     */
    public function precinct(): BelongsTo
    {
        return $this->belongsTo(Precinct::class);
    }

    /**
     * @return HasMany<Vote,Table>
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * @return BelongsTo<User,Table>
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
