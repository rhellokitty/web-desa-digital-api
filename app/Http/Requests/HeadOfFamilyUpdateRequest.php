<?php

namespace App\Http\Requests;

use App\Models\HeadOfFamily;
use Illuminate\Foundation\Http\FormRequest;

class HeadOfFamilyUpdateRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'email' => 'sometimes|string|email|unique:users,email,' . HeadOfFamily::find($this->route('head_of_family'))->user_id,
            'password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'identity_number' => 'sometimes|integer',
            'gender' => 'sometimes|string|in:male,female',
            'date_of_birth' => 'sometimes|date',
            'phone_number' => 'sometimes|string',
            'occupation' => 'sometimes|string',
            'marital_status' => 'sometimes|string|in:single,married',
        ];
    }
}
