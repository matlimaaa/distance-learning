<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCourseValidate extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $uuid = $this->uuid ?? '';
        
        return [
            'name' => [
                'required',
                'min:3',
                'max:255',
                "unique:courses,name,{$uuid},uuid",
            ],
            'description' => [
                'nullable',
                'string',
                'min:3',
                'max:9999',
            ]
        ];
    }
}
