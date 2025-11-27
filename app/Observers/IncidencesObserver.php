<?php

namespace App\Observers;

use App\Mail\SendIncidenceEmail;
use App\Models\Absence;
use App\Models\Incidences;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class IncidencesObserver
{
    public function creating(Incidences $incidences)
    {
        // clean values hidden other options in form
        switch ($incidences->type) {
            case 'owner_incidence':
            case 'specific_activities':
                $incidences->person_id = $incidences->stall->titular_id;
                $incidences->market_id = null;
                break;
            case 'general_incidence':
                // if not market check cache
                if (!$incidences->market_id) {
                    $incidences->market_id = Cache::get('market' . auth()->user()->id);
                }
                $incidences->person_id = null;
                $incidences->stall_id = null;
                break;
        }

        $incidences->user_id = backpack_user()->id;
    }

    public function created(Incidences $incidences)
    {
        if ($incidences->add_absence) {
            Absence::create([
                'person_id' => $incidences->person_id,
                'stall_id' => $incidences->stall->id,
                'start' => $incidences->date_incidence,
                'end' => $incidences->date_incidence,
                'type' => 'justificada',
            ]);
        }

        if ($incidences->send) {
            Mail::to($incidences->contact_email->email)->queue(new SendIncidenceEmail($incidences));
            $incidences->send_at = Carbon::now()->toDateString();
            $incidences->save();
        }
    }

    public function updating(Incidences $incidences)
    {
        if ($incidences->getOriginal('status') == 'pending' && $incidences->status == 'solved') {
            $incidences->date_solved = Carbon::now()->toDateString();
        }
    }

    public function deleting(Incidences $incidences)
    {
        foreach($incidences->images as $image){
            \Storage::disk('local')->delete($image);
        }
    }
}
