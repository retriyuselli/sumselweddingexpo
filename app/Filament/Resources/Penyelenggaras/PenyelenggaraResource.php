<?php

namespace App\Filament\Resources\Penyelenggaras;

use App\Filament\Clusters\Penyelenggara as PenyelenggaraCluster;
use App\Filament\Resources\Penyelenggaras\Pages\CreatePenyelenggara;
use App\Filament\Resources\Penyelenggaras\Pages\EditPenyelenggara;
use App\Filament\Resources\Penyelenggaras\Pages\ListPenyelenggaras;
use App\Filament\Resources\Penyelenggaras\RelationManagers\GalleriesRelationManager;
use App\Filament\Resources\Penyelenggaras\Schemas\PenyelenggaraForm;
use App\Filament\Resources\Penyelenggaras\Tables\PenyelenggarasTable;
use App\Models\Penyelenggara;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenyelenggaraResource extends Resource
{
    protected static ?string $model = Penyelenggara::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $cluster = PenyelenggaraCluster::class;

    public static function form(Schema $schema): Schema
    {
        return PenyelenggaraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenyelenggarasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            GalleriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPenyelenggaras::route('/'),
            'create' => CreatePenyelenggara::route('/create'),
            'edit' => EditPenyelenggara::route('/{record}/edit'),
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
