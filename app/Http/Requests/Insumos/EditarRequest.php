<?php

namespace App\Http\Requests\Insumos;

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
            'fk_nomenclatura' =>'required|integer',
            'fk_marca'        =>'required|integer',
            'fk_modelo'       =>'required|integer',
            'fk_categoria'    =>'required|integer',
            'fk_color'        =>'nullable|integer',
            'codigo'          =>'required|string',
            'usuario'         =>'required|string',
            'id_insumo'       =>'required|integer'
        ];
    }
}
