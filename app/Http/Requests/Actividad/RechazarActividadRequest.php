<?php

namespace App\Http\Requests\Actividad;

use Illuminate\Foundation\Http\FormRequest;

class RechazarActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('rechazar', $this->route('actividad'));
    }

    public function rules(): array
    {
        return [
            'motivo' => ['required', 'string', 'min:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'motivo.required' => 'Indica el motivo del rechazo — quedará visible para la presidencia.',
        ];
    }
}
