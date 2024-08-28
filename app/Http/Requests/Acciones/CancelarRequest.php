<?php

namespace App\Http\Requests\Acciones;

use Illuminate\Foundation\Http\FormRequest;

class CancelarRequest extends FormRequest
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
            "id_accion" =>'required|integer',
            "usuario"    =>'required|string',
            "detalles" =>'sometimes|array|min:1',
            "detalles.*.id_detalle" =>'required|integer',
            "detalles.*.fk_insumo" =>'required|integer',
            "detalles.*.cantidad_solicitada" =>'required|integer'
        ];
    }
}
