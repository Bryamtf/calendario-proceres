<?php

namespace App\Http\Requests\Actividad;

use Illuminate\Foundation\Http\FormRequest;

class StoreActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Actividad::class);
    }

    public function rules(): array
    {
        return [
            'organizacion_id' => ['nullable', 'exists:organizaciones,id'],

            'nombre' => ['required', 'string', 'max:150'],
            'fecha' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'lugar' => ['required', 'string', 'max:150'],
            'objetivo' => ['required', 'string'],
            'descripcion' => ['nullable', 'string'],

            'asistencia_esperada' => ['nullable', 'integer', 'min:0'],
            'miembros_nuevos' => ['nullable', 'integer', 'min:0'],
            'amigos_ensenanza' => ['nullable', 'integer', 'min:0'],
            'miembros_menos_activos' => ['nullable', 'integer', 'min:0'],

            'participantes' => ['nullable', 'array'],
            'participantes.*.tipo' => ['required_with:participantes', 'in:miembro_nuevo,amigo_ensenanza,menos_activo'],
            'participantes.*.nombre' => ['required_with:participantes', 'string', 'max:150'],

            'solicita_presupuesto' => ['boolean'],

            'presupuesto_items' => ['required_if:solicita_presupuesto,true', 'array', 'min:1'],
            'presupuesto_items.*.categoria_presupuesto_id' => ['nullable', 'exists:categorias_presupuesto,id'],
            'presupuesto_items.*.monto' => ['required_with:presupuesto_items', 'numeric', 'min:0.01'],
            'presupuesto_items.*.justificacion' => ['nullable', 'string'],

            'recursos' => ['nullable', 'array'],
            'recursos.*' => ['exists:recursos,id'],
            'recursos_detalle' => ['nullable', 'array'], // [recurso_id => detalle libre]
        ];
    }

    public function messages(): array
    {
        return [
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'presupuesto_items.required_if' => 'Agrega al menos un monto si la actividad solicita presupuesto.',
            'organizacion_id.required' => 'Selecciona para qué organización es esta actividad.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->user()->organizacion_id && !$this->filled('organizacion_id')) {
                $validator->errors()->add('organizacion_id', 'Selecciona para qué organización es esta actividad.');
            }
        });
    }
}
