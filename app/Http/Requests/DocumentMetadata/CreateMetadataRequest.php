<?php

namespace App\Http\Requests\DocumentMetadata;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateMetadataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'metadata_type_id' => ['required', 'int'],
            'is_required' => ['boolean', 'required']
        ];
    }
}
