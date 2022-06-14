<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6',
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'sometimes|string|email|max:255|unique:users',
                    'password' => 'sometimes|string|min:6',
                ];

            default:
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'sometimes|string|email|max:255|unique:users',
                ];
        }
    }
}
