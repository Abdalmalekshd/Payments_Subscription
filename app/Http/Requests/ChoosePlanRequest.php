<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChoosePlanRequest extends FormRequest
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
            'selectedPlanid' =>'required|exists:plans,id',
            'plan_type'      =>'required|in:month,year',
            'price_id'      =>'required',



        ];
    }



    public function messages()
    {
        return [
            'selectedPlanid.required' =>'Please choose a plan first',
            'selectedPlanid.exists'   =>'The plan you choose doe\'s not exist',
            'plan_type.required'      =>'Please choose the plan type',
            'plan_type'               =>'Plan type you choose doe\'s not exist',




        ];
    }
}
