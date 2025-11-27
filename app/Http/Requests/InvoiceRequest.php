<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
        $years = 'required_without_all:day,month';
        if (isset(request()->years)){
            $years .= '|date';
        }

        $month = 'required_without_all:day,years';
        if (isset(request()->month)){
            $month .= '|date';
        }

        $day = 'required_without_all:month,years';
        if (isset(request()->day)){
            $day .= '|date';
        }

        return [
            'years' => $years,
            'month' => $month,
            'day' => $day,
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
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
