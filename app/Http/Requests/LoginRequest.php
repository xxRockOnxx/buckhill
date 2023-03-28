<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        // Example response on Swagger docs for failed login is always 422 with just a message.
        // If validation is wanted, uncomment the following lines.
        //
        // return [
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ];

        return [];
    }
}
