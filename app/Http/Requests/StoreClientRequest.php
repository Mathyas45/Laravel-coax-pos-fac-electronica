<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambiar a true para permitir la autorización
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:250',
            'surname' => 'nullable|string|max:250',
            'full_name' => 'required|string|max:250',
            'phone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:250|unique:clients,email',
            'type_client' => 'required|integer|in:1,2',
            'type_document' => 'nullable|string|max:150',
            'n_document' => 'required|string|max:50|unique:clients,n_document',
            'gender' => 'nullable|in:M,F',
            'birth_date' => 'nullable|date|before:today',
            'user_id' => 'nullable|exists:users,id',
            'address' => 'nullable|string|max:250',
            'ubigeo_distrito' => 'nullable|string|max:25',
            'ubigeo_provincia' => 'nullable|string|max:25',
            'ubigeo_region' => 'nullable|string|max:25',
            'distrito' => 'nullable|string|max:80',
            'provincia' => 'nullable|string|max:80',
            'region' => 'nullable|string|max:80',
            'state' => 'nullable|integer|in:0,1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'El nombre completo es obligatorio.',
            'type_client.required' => 'El tipo de cliente es obligatorio.',
            'type_client.in' => 'El tipo de cliente debe ser 1 (Cliente Normal) o 2 (Empresa).',
            'n_document.required' => 'El número de documento es obligatorio.',
            'n_document.unique' => 'Este número de documento ya está registrado.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'gender.in' => 'El género debe ser M (Masculino) o F (Femenino).',
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'nombre completo',
            'type_client' => 'tipo de cliente',
            'n_document' => 'número de documento',
            'birth_date' => 'fecha de nacimiento',
            'user_id' => 'usuario',
        ];
    }
}
