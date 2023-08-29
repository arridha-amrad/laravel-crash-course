<?php

namespace App\Http\Requests;

use App\Util\HasJsonResponseValidation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    use HasJsonResponseValidation;
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
        return [
            'title' => 'required|string|min:5|max:50|unique:books,title',
            'stocks' => 'required',
            'year' => 'required',
            'coverUrl' => 'nullable|min:10'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->setValidationResult($validator);
    }
}
