<?php

namespace App\Filament\Resources\PengeluaranLains;

use App\Filament\Clusters\Keuangan as KeuanganCluster;
use App\Filament\Resources\PengeluaranLains\Pages\CreatePengeluaranLain;
use App\Filament\Resources\PengeluaranLains\Pages\EditPengeluaranLain;
use App\Filament\Resources\PengeluaranLains\Pages\ListPengeluaranLains;
use App\Filament\Resources\PengeluaranLains\Schemas\PengeluaranLainForm;
use App\Filament\Resources\PengeluaranLains\Tables\PengeluaranLainsTable;
use App\Models\PengeluaranLain;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengeluaranLainResource extends Resource
{
    protected static ?string $model = PengeluaranLain::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $cluster = KeuanganCluster::class;

    public static function form(Schema $schema): Schema
    {
        return PengeluaranLainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengeluaranLainsTable::configure($table);
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
            'index' => ListPengeluaranLains::route('/'),
            'create' => CreatePengeluaranLain::route('/create'),
            'edit' => EditPengeluaranLain::route('/{record}/edit'),
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
