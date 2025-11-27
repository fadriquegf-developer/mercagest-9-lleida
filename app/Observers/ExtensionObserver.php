<?php

namespace App\Observers;

use App\Models\Extension;

class ExtensionObserver
{
    public function creating(Extension $extension)
    {
        $stall = $extension->stall()->first();
        if ($stall) {
            $extension->person_id = $stall->titular_info->id;
            $extension->length = $stall->length;
        }
    }


    public function updating(Extension $extension)
    {
        $stall = $extension->stall()->first();
        if ($stall) {
            $extension->person_id = $stall->titular_info->id;
            $extension->length = $stall->length;
        }
    }
}
