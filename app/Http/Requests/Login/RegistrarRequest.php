<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'apellido' =>'required|string',
            'usuario' =>'required|string',
            'fk_despacho'=>'required|integer',
            'fk_rol' =>'required|integer',
            'email' => 'required|email|unique:users,email|max:255',
            'password' =>'required|string',
        ];
    }
}
