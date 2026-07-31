<?php

namespace App\Filament\Resources\Partisipasis\Pages;

use App\Filament\Resources\Partisipasis\PartisipasiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPartisipasi extends EditRecord
{
    protected static string $resource = PartisipasiResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $hargaJual = (int) str_replace(',', '', (string) ($data['harga_jual'] ?? 0));
        $diskon = (int) str_replace(',', '', (string) ($data['diskon'] ?? 0));
        $barter = ! empty($data['is_barter'])
            ? (int) str_replace(',', '', (string) ($data['barter_nominal'] ?? 0))
            : 0;

        $data['harga_bersih'] = max(0, $hargaJual - $diskon - $barter);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
