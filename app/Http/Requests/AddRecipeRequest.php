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
            'product' => 'required|min:1'
        ];
    }
}
