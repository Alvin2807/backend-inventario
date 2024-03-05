<?php

namespace App\Http\Requests\Acciones;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmacionParcialRequest extends FormRequest
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
            'id_accion'  =>'required|integer',
            'id_detalle' =>'required|integer',
            'cantidad_confirmada' =>'required|integer',
            'cantidad_pendiente' =>'required|integer',
            'cantidad_solicitada' =>'required|integer',
            'usuario' =>'required|string',
            'fk_producto' =>'required|integer',
            'id_localizacion'=>'required|integer'
          /*   'usuario' =>'required|string',
            'no_nota' =>'required|string',
            'titulo_nota' =>'required|string',
            'cantidad_confirmada' =>'required|integer',
            'cantidad_pendiente' =>'required|integer',
            'fk_despacho_asignado' =>'required|integer',
            'observacion' =>'required|string',
            'detalle'     =>'sometimes|array|min:1',
            'detalle.*.id_detalle' =>'required|integer',
            'detalle.*.cantidad_confirmada' =>'required|integer',
            'detalle.*.cantidad_solicitada' =>'required|integer',
            'detalle.*.cantidad_pendiente'  =>'required|integer',
            'detalle.*.cantidad_solicitada_productos' =>'required|integer',
            'detalle.*.fk_producto' =>'required|integer',
            'detalle.*.fk_localizacion' =>'required|integer' */
        ];
    }
}
