<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
           
                'name' => 'required|min:4|max:100',
                'description' => 'required|min:4|max:200',
                'stock' => 'required'
        
            //
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name required minimo 4 characters',
            'name.max' => 'Name required maximum 100 characters',
            'description.required' => 'Description is required',
            'description.min' => 'Desription required minimo 4 characters',
            'stock' => 'Stock is required'
        ];
    }
}
