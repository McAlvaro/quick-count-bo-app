<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Vote;

class VoteObserver
{
    /**
     * Handle the Vote "created" event.
     */
    public function created(Vote $vote): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'model_type' => Vote::class,
            'model_id' => $vote->id,
            'action' => 'created',
            'changes' => ['after' => $vote->toArray()],
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Handle the Vote "updated" event.
     */
    public function updated(Vote $vote): void
    {
        if ($vote->wasChanged()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'model_type' => Vote::class,
                'model_id' => $vote->id,
                'action' => 'updated',
                'changes' => [
                    'before' => array_intersect_key($vote->getOriginal(), $vote->getChanges()),
                    'after' => $vote->getChanges(),
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the Vote "deleted" event.
     */
    public function deleted(Vote $vote): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'model_type' => Vote::class,
            'model_id' => $vote->id,
            'action' => 'deleted',
            'changes' => ['before' => $vote->toArray()],
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Handle the Vote "restored" event.
     */
    public function restored(Vote $vote): void
    {
        //
    }

    /**
     * Handle the Vote "force deleted" event.
     */
    public function forceDeleted(Vote $vote): void
    {
        //
    }
}
