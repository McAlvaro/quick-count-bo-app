<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $guarded = [];

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

    public function precinct()
    {
        // Como la tabla vote no tiene precinct_id directo, pero table sí,
        // debes hacer un "hasOneThrough" o definir un accesor para simplificar:

        return $this->hasOneThrough(
            Precinct::class,
            Table::class,
            'id',           // Foreign key on tables (PK)
            'id',           // Foreign key on precincts (PK)
            'table_id',     // Local key on votes
            'precinct_id'   // Local key on tables
        );
    }
}
