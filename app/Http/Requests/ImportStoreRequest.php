<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportStoreRequest extends FormRequest
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
        $type = $this->input('type');
        $config = config("imports.types.{$type}");

        $rules = [
            'type' => 'required|in:' . collect(config('imports.types'))->keys()->implode(','),
        ];

        if ($config && isset($config['files'])) {
            foreach ($config['files'] as $key => $file) {
                $rules[$key] = 'required|file|mimes:csv,xlsx';
            }
        }

        return $rules;
    }
}
