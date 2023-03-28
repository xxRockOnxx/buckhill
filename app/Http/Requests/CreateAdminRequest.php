<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->is_admin;
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

            // This seems to be a typo.
            // The CreateUserRequest uses 'is_marketing' but this uses 'marketing'.
            // Using `marketing` here because that's what the Swagger docs say.
            'marketing' => 'boolean',
        ];
    }
}
