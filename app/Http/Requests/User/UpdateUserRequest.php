<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('usuario'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($this->route('usuario'))],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'organizacion_id' => ['nullable', 'exists:organizaciones,id'],
            'activo' => ['boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $rol = Role::find($this->input('role_id'));

            if ($rol && $rol->nombre === 'Presidencia' && !$this->filled('organizacion_id')) {
                $validator->errors()->add('organizacion_id', 'Selecciona la organización de esta presidencia.');
            }
        });
    }
}
