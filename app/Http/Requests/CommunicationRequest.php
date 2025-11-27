<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CommunicationRequest extends FormRequest
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
            'title' => 'required',
            'message' => 'required',
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
            'type' => __('backpack.communications.type'),
            'title' => __('backpack.communications.title'),
            'message' => __('backpack.communications.message'),
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
            'market_id.required_without_all' => __('backpack.communications.errors.filter_required'),
            'sector_id.required_without_all' => __('backpack.communications.errors.filter_required'),
            'auth_prod_id.required_without_all' => __('backpack.communications.errors.filter_required'),
            'persons.required_without_all' => __('backpack.communications.errors.filter_required'),
            'stalls.required_without_all' => __('backpack.communications.errors.filter_required'),
        ];
    }
}
