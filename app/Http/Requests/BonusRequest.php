<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class BonusRequest extends FormRequest
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
            'stall_id' => 'required_if:select,individual',
            'marketgroup_id' => 'required_if:select,group',
            'type' => 'required',
            'amount' => 'required',
            'start_at' => 'required',
            'ends_at' => 'required',
            'reason' => 'required',
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
            'select' => __('backpack.bonuses.type'),
            'marketgroup_id' => __('backpack.stalls.market_group'),
            'stall_id' => __('backpack.bonuses.stall_id'),
            'type' => __('backpack.bonuses.amount_type'),
            'amount' => __('backpack.bonuses.amount'),
            'start_at' => __('backpack.bonuses.start_at'),
            'ends_at' => __('backpack.bonuses.ends_at'),
            'reason' => __('backpack.bonuses.reason'),
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
            'select.required_if' => __('validation.required', [':attribute' => __('backpack.bonuses.type')]),
            'marketgroup_id.required_if' => __('validation.required', [':attribute' => __('backpack.stalls.market_group')]),
        ];
    }
}
