<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Vendor */
class VendorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_vendor' => $this->nama_vendor,
            'slug' => $this->slug,
            'kota' => $this->kota,
            'logo' => $this->logo,
            'jenis_usaha' => $this->whenLoaded('jenisUsaha', fn () => [
                'id' => $this->jenisUsaha?->id,
                'nama_jenis_usaha' => $this->jenisUsaha?->nama_jenis_usaha,
            ]),
        ];
    }
}
