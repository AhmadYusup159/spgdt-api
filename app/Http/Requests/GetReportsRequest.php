<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetReportsRequest extends FormRequest
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
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50',
            'type' => 'sometimes|string|max:50',
            'status' => 'sometimes|string|max:50',
            'city' => 'sometimes|string|max:100',
            'start_date' => 'sometimes|date_format:Y-m-d',
            'end_date' => 'sometimes|date_format:Y-m-d',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'limit.max' => 'The limit must not exceed 50 records.',
            'start_date.date_format' => 'The start date must be in the format Y-m-d.',
            'end_date.date_format' => 'The end date must be in the format Y-m-d.',
        ];
    }

    /**
     * Get the data to be validated from the request.
     *
     * @return array
     */
    public function validated(): array
    {
        return parent::validated();
    }
}
