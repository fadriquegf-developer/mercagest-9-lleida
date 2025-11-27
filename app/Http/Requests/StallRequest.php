<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StallRequest extends FormRequest
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
            'market_id' => 'required',
            // 'num' => 'required|' . Rule::unique('stalls')
            //     ->when(fn ($query) => $query->where('market_id', request()->market_id))
            //     ->ignore(request()->route()->id),
            'length' => 'required|max:2147483647',
            'market_group_id' => 'required',
            'auth_prods' => 'required',
            'classe_id' => 'required',
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
            'market_id' => __('backpack.stalls.market_id'),
            'num' => __('backpack.stalls.num'),
            'length' => __('backpack.stalls.space'),
            'market_group_id' => __('backpack.stalls.market_group'),
            'auth_prods' => __('backpack.stalls.auth_prod_id'),
            'classe_id' => __('backpack.stalls.classe_id'),
            'type' => __('backpack.stalls.type'),
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
