<?php

namespace App\Http\Requests\Acciones;

use Illuminate\Foundation\Http\FormRequest;

class EditarSolicitudRequest extends FormRequest
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
            'id_accion' =>'required|integer',
            'no_nota' =>'required|string',
            'titulo_nota' =>'required|string',
            'fk_despacho_asignado' =>'required|integer',
            'observacion' =>'required|string|min:80',
            'usuario' =>'required|string',
            'sometimes' =>'sometimes|array|min:1',
            'detalle.*.cantidad_solicitada' =>'required|integer',
            'detalle.*.id_detalle' =>'nullable|integer',
            'detalle.*.observacion' =>'nullable|integer',
            'detalle.*.fk_producto' =>'required|integer'
        ];
    }
}
