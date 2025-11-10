<?php

namespace App\Filament\Resources\Partisipasis;

use App\Filament\Resources\Partisipasis\Pages\CreatePartisipasi;
use App\Filament\Resources\Partisipasis\Pages\EditPartisipasi;
use App\Filament\Resources\Partisipasis\Pages\ListPartisipasis;
use App\Filament\Resources\Partisipasis\Schemas\PartisipasiForm;
use App\Filament\Resources\Partisipasis\Tables\PartisipasisTable;
use App\Models\Partisipasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartisipasiResource extends Resource
{
    protected static ?string $model = Partisipasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return PartisipasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartisipasisTable::configure($table);
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
            'index' => ListPartisipasis::route('/'),
            'create' => CreatePartisipasi::route('/create'),
            'edit' => EditPartisipasi::route('/{record}/edit'),
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
