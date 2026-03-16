<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required | string | max:200',
            'email' => 'required | unique:users | email',
            'password' => 'required | confirmed | min:8'
        ];
    }
    public function messages()
    {
        return [
            'email.unique' => "L'adresse email est déjà utilisée.",
            'password.min' => "Le mot de passe doit contenir au moins 8 caractères."
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        return response()->json([
            "success" => false,
            "message" => "Erreur de validation.",
            "errors" => $errors->messages()
        ]);
    }
}
