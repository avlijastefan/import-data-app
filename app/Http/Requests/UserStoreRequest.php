<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'The username field is required.',
            'username.unique'   => 'This username is already taken.',
            'username.max'      => 'The username may not be greater than 255 characters.',

            'password.required' => 'The password field is required.',
            'password.min'      => 'The password must be at least 6 characters.',
            'password.confirmed'=> 'The password confirmation does not match.',

            'permissions.*.exists' => 'The selected permission is invalid.',
        ];
    }

    /**
     * Hash password and clean permissions AFTER validation
     */
    protected function passedValidation(): void
    {
       $this->merge([
        'username' => $this->username,
        'password' => $this->filled('password') ? bcrypt($this->password) : null,
        'permissions' => collect($this->permissions ?? [])
            ->filter()
            ->values()
            ->toArray(),
        ]);
    }
}
