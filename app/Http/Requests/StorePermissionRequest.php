<?php
// app/Http/Requests/StorePermissionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:permissions',
            'guard_name' => 'string|in:web,api',
            'module_id' => 'required|exists:modules,id',
            'description' => 'nullable|string|max:500'
        ];
    }
}
