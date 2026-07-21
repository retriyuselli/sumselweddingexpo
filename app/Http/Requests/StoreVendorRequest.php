<?php

namespace App\Http\Requests;

use App\Enums\CategoryTier;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->routeIs('vendors.store')) {
            return $this->user()?->can('create', \App\Models\Vendor::class) ?? false;
        }

        return $this->user() !== null;
    }

    public function rules(): array
    {
        if ($this->routeIs('exhibitor.store')) {
            return $this->exhibitorRules();
        }

        $rules = [
            'nama_pendaftar' => ['required', 'string', 'max:255'],
            'nama_vendor' => ['required', 'string', 'max:255'],
            'jenis_usaha_id' => ['required', 'exists:jenis_usahas,id'],
            'alamat' => ['required', 'string'],
            'kota' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:vendors,email'],
            'nama_pic' => ['required', 'string', 'max:255'],
            'no_wa_pic' => ['required', 'string', 'max:20'],
            'user_id' => ['nullable', 'integer', 'exists:users,id', 'unique:vendors,user_id'],
        ];

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    protected function exhibitorRules(): array
    {
        $participation = [
            'paket' => ['required', Rule::enum(CategoryTier::class)],
            'lokasi_booth' => ['nullable', 'string', 'max:100'],
            'pendamping_tenant' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->existingVendorForUser()) {
            return $participation;
        }

        return array_merge([
            'nama_pendaftar' => ['required', 'string', 'max:255'],
            'nama_vendor' => ['required', 'string', 'max:255'],
            'jenis_usaha_id' => ['required', 'exists:jenis_usahas,id'],
            'alamat' => ['required', 'string'],
            'kota' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:vendors,email'],
            'nama_pic' => ['required', 'string', 'max:255'],
            'no_wa_pic' => ['required', 'string', 'max:20'],
        ], $participation);
    }

    public function existingVendorForUser(): ?Vendor
    {
        if (! $this->user()) {
            return null;
        }

        return Vendor::where('user_id', $this->user()->id)->first();
    }

    /**
     * Vendor identity fields only.
     *
     * @return array<string, mixed>
     */
    public function vendorAttributes(): array
    {
        return $this->only([
            'nama_pendaftar',
            'nama_vendor',
            'jenis_usaha_id',
            'alamat',
            'kota',
            'no_telepon',
            'email',
            'nama_pic',
            'no_wa_pic',
        ]);
    }

    /**
     * Expo participation preference fields (exhibitor form).
     *
     * @return array<string, mixed>
     */
    public function participationAttributes(): array
    {
        return $this->only([
            'paket',
            'lokasi_booth',
            'pendamping_tenant',
        ]);
    }
}
