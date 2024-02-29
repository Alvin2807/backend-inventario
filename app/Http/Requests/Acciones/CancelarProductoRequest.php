<?php

namespace App\Http\Requests\Acciones;

use Illuminate\Foundation\Http\FormRequest;

class CancelarProductoRequest extends FormRequest
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
            'id_detalle' =>'required|integer',
            'fk_producto' =>'required|integer',
            'cantidad_solicitada_productos'=>'required|integer',
            'cantidad_solicitada' =>'required|integer',
            'usuario' =>'required|string'
        ];
    }
}
