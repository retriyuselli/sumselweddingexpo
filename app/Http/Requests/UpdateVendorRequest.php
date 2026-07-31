<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $vendor = $this->route('vendor');

        return $this->user()?->can('update', $vendor) ?? false;
    }

    public function rules(): array
    {
        $vendor = $this->route('vendor');

        return [
            'nama_pendaftar' => ['required', 'string', 'max:255'],
            'nama_vendor' => ['required', 'string', 'max:255'],
            'jenis_usaha_id' => ['required', 'exists:jenis_usahas,id'],
            'alamat' => ['required', 'string'],
            'kota' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('vendors', 'email')->ignore($vendor?->id),
            ],
            'nama_pic' => ['required', 'string', 'max:255'],
            'no_wa_pic' => ['required', 'string', 'max:20'],
        ];
    }
}
