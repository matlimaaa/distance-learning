<?php

namespace App\Observers;

use App\Models\Course;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CourseObserver
{
    /**
     * Handle the Course "creating" event.
     */
    public function creating(Course $course): void
    {
        $course->uuid = strval(Str::uuid());
    }

    /**
     * Handle the Course "creating" event.
     */
    public function created(): void
    {
        Cache::forget('all_courses');
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
