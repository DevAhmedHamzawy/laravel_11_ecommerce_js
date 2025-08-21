<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'description' => 'required',
            'active' => 'sometimes|boolean',
        ];

        if($this->method() == 'PATCH') {
            $rules['name'] = 'required|unique:brands,name,' . $this->brand->id;
            $rules['main_image'] = 'mimes:jpeg,jpg,png,gif|sometimes|max:10000';
        }else{
            $rules['name'] = 'required|unique:brands,name';
            $rules['main_image'] = 'mimes:jpeg,jpg,png,gif|required|max:10000';
        }

        return $rules;
    }
}
