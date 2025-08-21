<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\FlashSale;

class FlashSaleRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'discount' => 'required|numeric|min:0|between:0,100',
        ];
    }

    /**
     * Add custom validation after the base rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            $start = $this->input('start_time');
            $end = $this->input('end_time');

            $query = FlashSale::query();

            // استبعاد الفلاش سيل الحالي لو بنعمل تحديث
            if ($this->route('flash_sale')) {
                $query->where('id', '!=', $this->route('flash_sale'));
            }

            $hasOverlap = $query->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                  });
            })->exists();

            if ($hasOverlap) {
                $validator->errors()->add('start_time', trans('flash_sale.intersection_date'));
            }
        });
    }
}
