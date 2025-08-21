<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'supplier_id' => 'required',
            'date' => 'required',
            'status' => 'required',
            'main_image' => 'mimes:jpeg,jpg,png,gif|sometimes|max:10000'
        ];

        if ($this->method() == 'PATCH') {
            $rules['reference_number'] = 'required|unique:purchases,reference_number,' . $this->purchase->id;
        }else{
            $rules['reference_number'] = 'required|unique:purchases,reference_number';
        }

        return $rules;
    }
}
