<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use App\Models\Absence;

class AbsenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required',
            'stalls' => 'required',
            'cause' => 'nullable',
            'start' => 'required',
            'end' => 'required'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->checkOverlap()) {
                $validator->errors()->add('field', trans('backpack.absences.errors.overlap_dates'));
            }
        });
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'type' => __('backpack.absences.id'),
            'stalls' => __('backpack.absences.person_id'),
            'person_id' => __('backpack.absences.person_id'),
            'cause' => __('backpack.absences.cause'),
            'start' => __('backpack.absences.start'),
            'end' => __('backpack.absences.end')
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    private function checkOverlap()
    {
        $result = Absence::where('stall_id', $this->input('stalls'))->byBusy($this->input('start'), $this->input('end'))->count();

        return $result > 0;
    }
}
