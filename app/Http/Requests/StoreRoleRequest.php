<?php
// app/Http/Requests/StoreRoleRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $roleId = $this->route('role') ?? $this->route('id');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'guard_name' => 'nullable|string', // Restrict to valid guards
            'permissions' => 'nullable|array',
            'permissions.*' => [
                'nullable',
                'integer', // Ensure permission IDs are integers
                // Rule::exists('permissions', 'id')->where('guard_name', $this->input('guard_name', 'web')),
            ],
        ];
        // return [
        //     'name' => 'required|string|max:255|unique:roles',
        //     'guard_name' => 'string',
        //     'permissions' => 'array',
        //     'permissions.*' => 'nullable',
        //     // 'permissions.*' => 'nullable|exists:permissions,name,guard_name,' . $this->route('id'),
        // ];
    }
    public function messages()
    {
        return [
            'name.unique' => 'The role name has already been taken.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
        ];
    }

    protected function prepareForValidation()
    {
        // Set default guard_name to 'web' if not provided
        if (!$this->has('guard_name')) {
            $this->merge(['guard_name' => 'web']);
        }
    }
}
