<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Lib\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $this->route('id'),
            // 'user_type' => ['sometimes', Rule::in(UserTypeEnum::values())],
            'status' => 'sometimes',
            // 'status' => ['sometimes', Rule::in(StatusEnum::values())],
            'phone' => 'sometimes|nullable|string|max:20',
            'date_of_birth' => 'sometimes|nullable|date',
            'bio' => 'sometimes|nullable|array',
            'bio.en' => 'sometimes|nullable|string',
            'bio.hi' => 'sometimes|nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::validationError($validator->errors())
        );
    }
}
