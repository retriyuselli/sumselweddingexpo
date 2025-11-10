<?php

namespace App\Filament\Resources\JenisUsahas;

use App\Filament\Resources\JenisUsahas\Pages\CreateJenisUsaha;
use App\Filament\Resources\JenisUsahas\Pages\EditJenisUsaha;
use App\Filament\Resources\JenisUsahas\Pages\ListJenisUsahas;
use App\Filament\Resources\JenisUsahas\Schemas\JenisUsahaForm;
use App\Filament\Resources\JenisUsahas\Tables\JenisUsahasTable;
use App\Models\JenisUsaha;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisUsahaResource extends Resource
{
    protected static ?string $model = JenisUsaha::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    public static function form(Schema $schema): Schema
    {
        return JenisUsahaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisUsahasTable::configure($table);
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
            'index' => ListJenisUsahas::route('/'),
            'create' => CreateJenisUsaha::route('/create'),
            'edit' => EditJenisUsaha::route('/{record}/edit'),
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
