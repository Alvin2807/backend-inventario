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
            'no_nota'                 =>'nullable|string',
            'fecha_nota'              =>'nullable|date',
            'fecha_confirmacion'      =>'nullable|date',
            'no_incidencia'           =>'nullable|string',
            'titulo_nota'             =>'nullable|string|max:80',
            'fk_tipo_accion'          =>'required|integer',
            'tipo_accion'             =>'required|string',
            'fk_despacho'             =>'required|integer',
            'observacion'             =>'nullable|string|min:80',
            'registrado_por'          =>'nullable|string',
            'cantidad_solicitada'     =>'nullable|integer',
            'usuario'                 =>'required|string',
            'detalle'                 =>'sometimes|array|min:1',
            'detalle.*.fk_insumo'     =>'required|integer',
            'detalle.*.cantidad_solicitada' =>'required|integer',
            //'detalle.*.observacion'   =>'nullable|string'
        ];
    }
}
