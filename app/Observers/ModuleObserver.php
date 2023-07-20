<?php

namespace App\Observers;

use App\Models\Module;
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
}
