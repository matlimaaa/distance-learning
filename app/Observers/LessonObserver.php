<?php

namespace App\Observers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LessonObserver
{
    /**
     * Handle the Lesson "creating" event.
     */
    public function creating(Lesson $lesson): void
    {
        $lesson->uuid = strval(Str::uuid());
    }

    /**
     * Handle the Course "updating" event.
     */
    public function updating(): void
    {
        Cache::forget('all_couses');
    }

    /**
     * Handle the Course "deleting" event.
     */
    public function deleting(): void
    {
        Cache::forget('all_couses');
    }
}
