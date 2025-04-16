<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequestStore extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'fone' => ['required', 'string', 'max:20'], // Ajuste o tamanho conforme necessário
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'professional_id' => [
                'required',
                'exists:users,id', // Garante que o ID existe na tabela users
                Rule::exists('users', 'id')->where('role', 'professional'), // Garante que o usuário é um profissional
            ],
        ];
    }
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
            'professional_id.exists' => 'O profissional selecionado não existe ou não é válido.'
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'fone' => preg_replace('/[^0-9]/', '', $this->input('fone', '')),
            'time' => $this->input('time', ''), // Garante que time tenha um valor, mas validação cuidará do resto
        ]);
    }
}
