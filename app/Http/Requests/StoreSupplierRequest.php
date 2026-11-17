<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses dijaga middleware role:admin di route
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:150'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'email'   => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }
}