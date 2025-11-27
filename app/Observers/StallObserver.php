<?php

namespace App\Observers;

use App\Models\Stall;

class StallObserver
{
    public function creating(Stall $stall)
    {
        if (!isset($stall->rate_id)){
            $market = $stall->market()->first();
            if ($market){
                $stall->rate_id = $market->rate_id;
            }
        }
    }


    public function updating(Stall $stall)
    {
        if (!isset($stall->rate_id)){
            $market = $stall->market()->first();
            if ($market){
                $stall->rate_id = $market->rate_id;
            }
        }
    }

    public function deleting(Stall $stall)
    {
        $stall->auth_prods()->detach();
    }
}
