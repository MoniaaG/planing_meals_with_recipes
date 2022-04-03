<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;


class UpdateCategoryRequest extends FormRequest
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
        // rozszerzenie reguł walidacji tylko dla StoreCategoryRequest
        // sprawdzenie unikalności nazwy kategorii
        \Validator::extend('unique_name',
            function ($attribute, $value, $parameters, $validator) {
                $result = Category::/*withTrashed()->*/where('id', '!=', $this->recipe_category->id)
                    ->where('name', $value)->count();
                return $result === 0;
            },
            __('validation.custom.name.unique', ['name' => $this->name])
        );

        return [
            'name'=> [
                'required',
                'max:50',
                'unique_name'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nazwa kategorii jest wymagana',
            'name.max:50' => 'Nazwa kategorii może zawierać maxymalnie 50 znaków',
            'name.unique_name' => 'Nazwa kategorii musi być unikalna',
        ];
    }
}
