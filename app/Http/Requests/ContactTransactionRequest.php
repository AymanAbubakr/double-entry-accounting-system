<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'credit_account_id' => 'required|integer',
            'debit_account_id' => 'required|integer',
            'amount' => 'required|numeric',
            'comment' => 'sometimes|string',
            'contact_id' => 'required|integer',
        ];
    }
}
