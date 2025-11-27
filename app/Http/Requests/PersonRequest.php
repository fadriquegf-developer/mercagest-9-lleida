<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidIban;

class PersonRequest extends FormRequest
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
            'dni' => 'required|min:9|max:11|unique:persons,dni,' . request()->route()->id,
            'name' => 'required|max:255',
            'type_address' => 'required',
            'address' => 'required',
            'number_address' => 'nullable',
            'extra_address' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'required',
            'iban' => ['nullable', new ValidIban()],
            //'date_domiciliacion' => 'required_with:iban',
            //'ref_domiciliacion' => 'required_with:iban'
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
            'dni' => __('backpack.persons.dni'),
            'name' => __('backpack.persons.name'),
            'type_address' => __('backpack.persons.type_address'),
            'address' => __('backpack.persons.address'),
            'number_address' => __('backpack.persons.number_address'),
            'extra_address' => __('backpack.persons.extra_address'),
            'email' => __('backpack.persons.email'),
            'phone' => __('backpack.persons.phone'),
            'date_domiciliacion' => __('backpack.persons.date_domiciliacion'),
            'ref_domiciliacion' => __('backpack.persons.ref_domiciliacion')
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
