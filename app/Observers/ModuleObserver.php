<?php

namespace App\Observers;

use App\Models\Module;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ModuleObserver
{
    /**
     * Handle the module "creating" event.
     */
    public function creating(Module $module): void
    {
        $module->uuid = strval(Str::uuid());
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
