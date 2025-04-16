<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequestUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Permite que qualquer usuário (autenticado ou não) crie um agendamento
        // Se precisar de regras específicas, ajuste aqui
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'fone' => ['required', 'string', 'max:20'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'professional_id' => [
                'required',
                'exists:users,id',
                Rule::exists('users', 'id')->where('role', 'professional'),
            ],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'date.required' => 'A data é obrigatória.',
            'fone.required' => 'O telefone é obrigatório.',
            'fone.regex' => 'O telefone deve ter 10 ou 11 dígitos (DD + número).',
            'date.date' => 'A data deve ser uma data válida.',
            'time.required' => 'O horário é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Insira um email válido.',
            'professional_id.required' => 'Você deve selecionar um profissional.',
            'professional_id.exists' => 'O profissional selecionado não existe ou não é válido.',
        ];
    }
}