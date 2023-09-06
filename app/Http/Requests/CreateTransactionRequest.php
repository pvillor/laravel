<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', 'gte:0.01'],
            'payer' => 'required',
            'payee' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'Valor é obrigatório',
            'value.numeric' => 'Valor deve ser um número',
            'value.gte' => 'Valor deve ser maior ou igual a 0.01',
        ];
    }
}
