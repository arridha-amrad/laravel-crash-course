<?php

namespace App\Http\Requests;

use App\Models\UserDetails;
use App\Util\HasJsonResponseValidation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserDetailsRequest extends FormRequest
{
    use HasJsonResponseValidation;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // dd($this->method());
        if ($this->method() === "POST") {
            return Auth::check();
        }
        if ($this->method() === "PUT" || $this->method() === "DELETE") {
            $userDetails = UserDetails::where('user_id', $this->user()->id)->first();
            return $this->user()->can('update-delete-userDetails', $userDetails);
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->method() === "DELETE") return [];
        return [
            'city' => 'required|string|min:3|max:20',
            'phoneNumber' => 'required|min:10|max:15',
            'postalCode' => 'required|min:3|max:20'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->setValidationResult($validator);
    }
}
