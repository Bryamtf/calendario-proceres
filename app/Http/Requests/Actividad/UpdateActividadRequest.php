<?php

namespace App\Http\Requests\Actividad;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('actividad'));
    }

    public function rules(): array
    {
        // Mismas reglas que al crear; el Service decide si el cambio es "menor"
        // (no reabre aprobación) o "mayor" (fecha/presupuesto/organización -> vuelve a Pendiente).
        return (new StoreActividadRequest())->rules();
    }
}
