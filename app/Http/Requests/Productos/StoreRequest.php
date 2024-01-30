<?php

namespace App\Http\Requests\Productos;

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
            'fk_categoria'    =>'required|integer',
            'fk_marca'        =>'required|integer',
            'fk_modelo'       =>'required|integer',
            'fk_nomenclatura' =>'required|integer',
            'fk_color'        =>'nullable|integer',
            'fk_unidad_medida'=>'required|integer',
            'codigo_producto' =>'required|string',
            'usuario'         =>'required|string'
        ];
    }
}
