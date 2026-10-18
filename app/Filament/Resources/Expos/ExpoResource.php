<?php

namespace App\Filament\Resources\Expos;

use UnitEnum;
use App\Filament\Resources\Expos\Pages\CreateExpo;
use App\Filament\Resources\Expos\Pages\EditExpo;
use App\Filament\Resources\Expos\Pages\ListExpos;
use App\Filament\Resources\Expos\Schemas\ExpoForm;
use App\Filament\Resources\Expos\Tables\ExposTable;
use App\Models\Expo;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExpoResource extends Resource
{
    protected static ?string $model = Expo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Expo';

    public static function form(Schema $schema): Schema
    {
        return ExpoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExposTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpos::route('/'),
            'create' => CreateExpo::route('/create'),
            'edit' => EditExpo::route('/{record}/edit'),
        ];
    }

    public static function qrAbsensiAction(): Action
    {
        return Action::make('qrAbsensi')
            ->label('QR Absensi')
            ->icon(Heroicon::OutlinedQrCode)
            ->modalHeading(fn (Expo $record): string => 'QR Absensi — '.$record->nama_expo)
            ->modalDescription(function (Expo $record): string {
                $mulai = $record->tanggal_mulai?->locale('id')->translatedFormat('d F Y') ?? '-';
                $selesai = $record->tanggal_selesai?->locale('id')->translatedFormat('d F Y') ?? '-';

                return "Tamu memindai kode ini, lalu mengisi email dan nomor telepon. Absensi pukul 09.00–22.00 WIB, dari {$mulai} sampai {$selesai}. Email dan nomor telepon hanya bisa tercatat sekali per hari.";
            })
            ->modalContent(fn (Expo $record) => view('filament.expos.qr-absensi', ['expo' => $record]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->modalWidth(Width::Medium);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery();
    }
}
