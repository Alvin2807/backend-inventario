<?php

namespace App\Http\Requests\Acciones;

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
            'no_nota'                 =>'required|string|min:11',
            'titulo_nota'             =>'required|string|max:80',
            'fk_tipo_accion'          =>'required|integer',
            'fk_despacho_solicitante' =>'required|integer',
            'fk_despacho_asignado'    =>'required|integer',
            'observacion'             =>'required|string|min:80',
            'usuario'                 =>'required|string',
            'detalle'                 =>'sometimes|array|min:1',
            'detalle.*.fk_producto'   =>'required|integer',
            'detalle.*.cantidad_solicitada' =>'required|integer',
            'detalle.*.observacion'   =>'nullable|string'
        ];
    }
}
