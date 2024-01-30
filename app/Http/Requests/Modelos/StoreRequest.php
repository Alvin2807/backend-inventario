<?php

namespace App\Http\Requests\Modelos;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'fk_marca' =>'required|integer',
            'usuario'  =>'required|string',
            "nombre_modelo" =>'required|string',
            "nomenclatura" =>'required|string'
        ];
    }
}
