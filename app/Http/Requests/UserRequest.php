<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'main_image' => 'mimes:jpeg,jpg,png,gif|sometimes|max:10000'
        ];

        if ($this->method() == 'PATCH') {
            $rules['email'] = 'required|email|unique:users,email,' . $this->user->id;
            $rules['password'] = 'nullable|min:8|max:25';
        }else{
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:8|max:25';
        }

        return $rules;
    }


}
