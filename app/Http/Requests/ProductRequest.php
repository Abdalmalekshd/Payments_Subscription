<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name'                  =>'required|min:3',
            'description'           =>'required|min:10',
            'image'                 =>'required|mimes:png,jpg',
            'price'                 =>'required|numeric',
            'quantity'              =>'required|numeric',
            'composited_products'   =>'required|array|min:1',
            'composited_quantities' =>'required|array|min:1|required_if:is_comopsite,on',

        ];
    }
}
