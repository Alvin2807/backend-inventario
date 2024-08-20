<?php

namespace App\Http\Requests\Acciones;

use Illuminate\Foundation\Http\FormRequest;

class EditarAccionRequest extends FormRequest
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
            'fk_accion'   =>'required|integer',
            'fk_tipo_accion' =>'required|integer',
            'fk_despacho' =>'required|integer',
            "titulo_nota" =>'required|string',
            "observacion" =>'required|string',
            "usuario"     =>'required|string',
            "detalles"    =>'sometimes|array|min:1',
            "detalles.*.fk_insumo" =>'required|integer',
            "detalles.*.cantidad_solicitada" =>'required|integer'
        ];
    }
}
