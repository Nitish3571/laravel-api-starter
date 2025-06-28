<?php
// app/Http/Requests/StoreModuleRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModuleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:modules',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ];
    }
}
