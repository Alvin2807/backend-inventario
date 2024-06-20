<?php

namespace App\Http\Requests\Nomenclaturas;

use Illuminate\Foundation\Http\FormRequest;

class EditarRequest extends FormRequest
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
            'nomenclatura'    =>'required|string',
            'id_nomenclatura' =>'required|integer',
            'usuario'         =>'required|string'
        ];
    }
}
