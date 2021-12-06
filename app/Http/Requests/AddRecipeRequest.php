<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddRecipeRequest extends FormRequest
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
            'share' => 'nullable',
            'description' => 'required',
            'short_description' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'small_image' => 'required|file',
            'big_image' => 'required|file',
            'products' => 'required|min:1',
            'products.*.quantity'  => 'min:1|required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Opisz przepisu wymagany!',
            'short_description.required' => 'Krótki opis przepisu wymagany!',
            'name.required' => 'Nazwa przepisu wymagana!',
            'category_id.required' => 'Kategoria przepisu wymagana!',
            'small_image.required' => 'Małe zdjęcie przepisu wymagane!',
            'big_image.required' => 'Duże zdjęcie przepisu wymagane!',
            'products.required' => 'Dodaj przynajmiej jeden produkt do przepisu!',
            'products.*.quantity.required'  => 'Ilość produktu wymagana!',
            'products.*.quantity.min:1'  => 'Ilość produktu musi być większa od zera!',
            'products.*.quantity.number'  => 'Ilość produktu musi być liczbą!'
        ];
    }
}
