<?php

namespace App\Observers;

use App\Models\Checklist;

class ChecklistObserver
{
    public function creating(Checklist $checklist)
    {
        $checklist->user_id = backpack_user()->id;
    }

    public function deleting(Checklist $checklist)
    {
        $checklist->checklist_answers->each(function ($answers) {
            $answers->delete();
        });
    }
}
