<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use RahulHaque\Filepond\Facades\Filepond;

class ChecklistAnswer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = ['is_check', 'comment', 'img', 'checklist_question_id', 'checklist_id'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function saveImage($field)
    {
        // Set filename
        $file_name = "img_{$this->checklist_question_id}";

        // Move the file to permanent storage
        // Automatic file extension set
        if ($field != $this->id) {
            $fileInfo = Filepond::field($field)->moveTo("checklist/{$this->checklist_id}/$file_name", 'local');
            $this->img = $fileInfo['location'];
        }
    }

    public function deleteImage()
    {
        if ($this->img) {
            Storage::disk('local')->delete($this->img);
        }
        $this->img = null;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function checklist_question()
    {
        return $this->belongsTo(ChecklistQuestion::class);
    }

    public function getImgUrl()
    {
        if ($this->img) return backpack_url('/storage/' . $this->img);
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
