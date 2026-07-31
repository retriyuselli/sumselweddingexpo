<?php

namespace App\Http\Requests;

use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $vendor = $this->route('vendor');
        $product = $this->route('productVendor');

        return $this->user()
            && $vendor
            && $product
            && (int) $vendor->user_id === (int) $this->user()->id
            && (int) $product->vendor_id === (int) $vendor->id;
    }

    public function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'dp_fixed' => ['nullable', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:1024'],
            'stok' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->has('deskripsi')) {
            $this->merge([
                'deskripsi' => HtmlSanitizer::clean($this->input('deskripsi')),
            ]);
        }
    }
}
