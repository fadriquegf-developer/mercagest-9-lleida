<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class IncidencesRequest extends FormRequest
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
        $rules = [];
        switch ($this->input('type')) {
            case 'general_incidence':
                $rules['title_general_incidence'] = 'required';
                $rules['description'] = 'required';
                break;
            case 'owner_incidence':
                $rules['title_owner_incidence'] = 'required';
                $rules['description'] = 'required';
                break;
            case 'specific_activities':
                $rules['title'] = 'required';
                break;
        }

        $rules['date_incidence'] = 'required';
        $rules['contact_email_id'] = 'required_if:send,==,1';

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => __('backpack.incidences.title'),
            'title_owner_incidence' => __('backpack.incidences.title'),
            'title_general_incidence' => __('backpack.incidences.title'),
            'description' => __('backpack.incidences.description'),
            'date_incidence' => __('backpack.incidences.date_incidence'),
            'contact_email_id' => __('backpack.incidences.contact_email_id')
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
}
