<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    /**
     * @return BelongsTo<Table,Vote>
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
    /**
     * @return BelongsTo<Candidate,Vote>
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
