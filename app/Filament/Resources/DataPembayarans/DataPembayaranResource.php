<?php

namespace App\Filament\Resources\DataPembayarans;

use App\Filament\Resources\DataPembayarans\Pages\CreateDataPembayaran;
use App\Filament\Resources\DataPembayarans\Pages\EditDataPembayaran;
use App\Filament\Resources\DataPembayarans\Pages\ListDataPembayarans;
use App\Filament\Resources\DataPembayarans\Schemas\DataPembayaranForm;
use App\Filament\Resources\DataPembayarans\Tables\DataPembayaransTable;
use App\Models\DataPembayaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataPembayaranResource extends Resource
{
    protected static ?string $model = DataPembayaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    public static function form(Schema $schema): Schema
    {
        return DataPembayaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataPembayaransTable::configure($table);
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
            'index' => ListDataPembayarans::route('/'),
            'create' => CreateDataPembayaran::route('/create'),
            'edit' => EditDataPembayaran::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
