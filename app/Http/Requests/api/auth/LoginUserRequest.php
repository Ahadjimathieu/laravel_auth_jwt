<?php

namespace App\Http\Requests\api\auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'email' => ['required','email'],
            'password' => ['required', 'min:8', 'max:50'],
        ];
    }


    /**
     * Send error message
     * @return message and error code.
     */
    public function failedValidation(Validator $validator)
    {
       return  response()->json('Erreur de validation', $validator->errors());
    }

    /**
     * Error message
     */
    public function messages()
    {
        return [
            'email.required' => ':attribute est obligatoire.',
            'email.email' => "Ce n'est pas un email.",
            'password.required' => 'Le :attribute est obligatoire.',
            'password.min' => 'Le :attribute doit contenir au moins 8 caractères.',
        ];
    }

    public function attributes()
    {
        return [
            'email' =>"L'adresse email",
            'password' => 'mot de passe',
        ];
    }
}
