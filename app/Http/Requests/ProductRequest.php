<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'tax_id' => 'required|exists:taxes,id',
            'unit_id' => 'required|exists:units,id',
            'translations.*.name' => 'required',
            'translations.*.mini_description' => 'required',
            'translations.*.description' => 'required',
            'sku' => 'required',
            'selling_price' => 'required|numeric|min:0|between:0,999999.99',
            'buying_price' => 'required|numeric|min:0|between:0,999999.99',
            'active' => 'sometimes|boolean',
        ];

        if ($this->method() == 'PATCH') {
            $rules['barcode'] = 'required|unique:products,barcode,' . $this->product->id;
            $rules['main_image'] = 'mimes:jpeg,jpg,png,gif|sometimes|max:10000';
        }else{
            $rules['barcode'] = 'required|unique:products,barcode';
            $rules['main_image'] = 'mimes:jpeg,jpg,png,gif|required|max:10000';
        }

        return $rules;

    }
}
