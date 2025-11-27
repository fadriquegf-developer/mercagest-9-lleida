<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendChecklistEmail;

class Checklist extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = ['checklist_type_id', 'origin_type', 'origen_id', 'all_ok'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function is_stall()
    {
        return $this->origin_type === Stall::class;
    }

    public function is_market()
    {
        return $this->origin_type === Market::class;
    }

    public function generatePdf()
    {
        $data = [];
        $data['entry'] = $this;
        $data['checklist'] = $this->checklist_type;
        $data['origin'] =  $this->origin;
        $data['groups'] = ChecklistAnswer::where('checklist_id', $this->id)->with('checklist_question.checklist_group')
            ->get()->groupBy('checklist_question.checklist_group.name');
        $view = '';

        if ($this->is_stall()) {
            $view = 'stall';
        } else if ($this->is_market()) {
            $view = 'market';
        }

        $pdf = PDF::loadView("tenant.".app()->tenant->name.".checklist.pdf.$view", $data);

        return $pdf;
    }

    public function showDate()
    {
        return \Carbon\Carbon::parse($this->created_at)
            ->setTimezone('Europe/Madrid')
            ->locale(app()->getLocale())
            ->isoFormat(config('backpack.base.default_datetime_format'));
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function origin()
    {
        return $this->morphTo();
    }

    public function checklist_type()
    {
        return $this->belongsTo(ChecklistType::class);
    }

    public function checklist_answers()
    {
        return $this->hasMany(ChecklistAnswer::class);
    }

    public function sendEmail()
    {
        if ($this->is_stall()) {
            $email = $this->origin->getTitular()->email;
            Mail::to($email)->queue(new SendChecklistEmail($this));
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
