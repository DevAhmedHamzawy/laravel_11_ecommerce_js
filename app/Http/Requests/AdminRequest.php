<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'name' => 'required',
            'role' => 'required',
        ];

        if ($this->method() == 'PATCH') {
            $rules['email'] = 'required|email|unique:admins,email,' . $this->admin->id;
            $rules['main_image'] = 'mimes:jpeg,jpg,png,gif|sometimes|max:10000';
            $rules['password'] = 'nullable|min:8|max:25';
        }else{
            $rules['email'] = 'required|email|unique:admins,email';
            $rules['main_image'] = 'mimes:jpeg,jpg,png,gif|required|max:10000';
            $rules['password'] = 'required|min:8|max:25';
        }

        return $rules;
    }
}
