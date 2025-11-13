<?php

namespace App\Filament\Resources\PenyelenggaraGalleries;

use App\Filament\Clusters\Penyelenggara as PenyelenggaraCluster;
use App\Filament\Resources\PenyelenggaraGalleries\Pages\CreatePenyelenggaraGallery;
use App\Filament\Resources\PenyelenggaraGalleries\Pages\EditPenyelenggaraGallery;
use App\Filament\Resources\PenyelenggaraGalleries\Pages\ListPenyelenggaraGalleries;
use App\Filament\Resources\PenyelenggaraGalleries\Schemas\PenyelenggaraGalleryForm;
use App\Filament\Resources\PenyelenggaraGalleries\Tables\PenyelenggaraGalleriesTable;
use App\Models\PenyelenggaraGallery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenyelenggaraGalleryResource extends Resource
{
    protected static ?string $model = PenyelenggaraGallery::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $cluster = PenyelenggaraCluster::class;

    public static function form(Schema $schema): Schema
    {
        return PenyelenggaraGalleryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenyelenggaraGalleriesTable::configure($table);
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
            'index' => ListPenyelenggaraGalleries::route('/'),
            'create' => CreatePenyelenggaraGallery::route('/create'),
            'edit' => EditPenyelenggaraGallery::route('/{record}/edit'),
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
