<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    /**
     * @return BelongsTo<Party,Candidate>
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * @return HasMany<Vote,Candidate>
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
