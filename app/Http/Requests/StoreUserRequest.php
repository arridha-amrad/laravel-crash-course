<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
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
        switch ($this->method()) {
            case "POST":
                return [
                    'firstName' => 'required|string|min:3|max:50',
                    'lastName' => 'nullable|string|min:3|max:50',
                    'username' => "required|string|min:5|max:15|unique:users,username",
                    'email' => "required|email|unique:users,email",
                    'password' => [
                        'required',
                        Password::min(6)->letters()->mixedCase()->numbers()->symbols()->uncompromised()
                    ]
                ];
                break;
            case "PUT":
                return [
                    'firstName' => 'required|string|min:3|max:50',
                    'lastName' => 'nullable|string|min:3|max:50',
                ];
                break;
            default:
                return [];
                break;
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password' => 'Password requires 6 characters with combination lowerCase, upperCase, number and symbol'
        ];
    }

    // return validation errors result as json-response
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
