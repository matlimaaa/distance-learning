<?php

namespace App\Observers;

use App\Models\Module;
use Illuminate\Support\Str;

class ModuleObserver
{
    /**
     * Handle the Course "creating" event.
     */
    public function creating(Module $course): void
    {
        $course->uuid = strval(Str::uuid());
    }
}
