<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductQuantityRequest extends FormRequest
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
            "products.*.quantity"  => 'min:1|required|numeric',
        ];
    }

    public function messages()
    {
        return [
            "products.*.quantity.required"  => 'Ilość produktu wymagana!',
            "products.*.quantity.min:1"  => 'Ilość produktu musi być większa od zera!',
            "products.*.quantity.number"  => 'Ilość produktu musi być liczbą!'
        ];
    }
}
