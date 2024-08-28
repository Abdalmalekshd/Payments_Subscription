<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'string|min:10',
            'plan_type' => 'required|array|min:1',
            'plan_type.*' => 'required|string|in:daily,weekly,monthly,yearly',
            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0',
            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0',
            'discount_limit' => 'nullable|array',
            'discount_limit.*' => 'nullable|date',
            'discount_type' => 'nullable|array',
            'discount_type.*' => 'nullable|string|in:fixed,percentage',

        ];
    }
}