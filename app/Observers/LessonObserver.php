<?php

namespace App\Observers;

use App\Models\Lesson;
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
}
