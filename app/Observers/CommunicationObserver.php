<?php

namespace App\Observers;

use App\Mail\SendCommunicationEmail;
use App\Models\Communication;
use App\Models\Stall;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommunicationObserver
{
    public function creating(Communication $communication)
    {
        $communication->user_id = auth()->user()->id;
    }

    public function deleting(Communication $communication)
    {
        $communication->stalls()->detach();
        $communication->persons()->detach();
    }
}
