<?php

namespace App\Http\Requests\Acciones;

use Illuminate\Foundation\Http\FormRequest;

class CancelarSolicitudRequest extends FormRequest
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
            'tipo_accion'=>'required|string',
            'usuario'=>'required|string',
            'detalle' =>'sometimes|array|min:1',
            'detalle.*.id_detalle' =>'required|integer',
            'detalle.*.fk_producto' =>'required|integer',
            /* 'detalle.*.cantidad_solicitada_productos' =>'required|integer',
            'detalle.*.cantidad_solicitada' =>'required|integer' */
        ];
    }
}
