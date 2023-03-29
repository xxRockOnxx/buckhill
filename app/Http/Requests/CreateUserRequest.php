<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'avatar' => 'nullable|exists:files,uuid',
            'is_marketing' => 'boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_marketing' => $this->boolean('is_marketing'),
        ]);
    }
}
