<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Table;

class TableObserver
{
    /**
     * Handle the Table "created" event.
     */
    public function created(Table $table): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'model_type' => Table::class,
            'model_id' => $table->id,
            'action' => 'created',
            'changes' => ['after' => $table->toArray()],
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Handle the Table "updated" event.
     */
    public function updated(Table $table): void
    {
        if ($table->wasChanged()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'model_type' => Table::class,
                'model_id' => $table->id,
                'action' => 'updated',
                'changes' => [
                    'before' => array_intersect_key($table->getOriginal(), $table->getChanges()),
                    'after' => $table->getChanges(),
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the Table "deleted" event.
     */
    public function deleted(Table $table): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'model_type' => Table::class,
            'model_id' => $table->id,
            'action' => 'deleted',
            'changes' => ['before' => $table->toArray()],
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Handle the Table "restored" event.
     */
    public function restored(Table $table): void
    {
        //
    }

    /**
     * Handle the Table "force deleted" event.
     */
    public function forceDeleted(Table $table): void
    {
        //
    }
}
