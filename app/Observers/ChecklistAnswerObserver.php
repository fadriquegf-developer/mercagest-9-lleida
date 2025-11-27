<?php

namespace App\Observers;

use App\Models\ChecklistAnswer;

class ChecklistAnswerObserver
{
    public function deleting(ChecklistAnswer $answer)
    {
        $answer->deleteImage();
    }
}
