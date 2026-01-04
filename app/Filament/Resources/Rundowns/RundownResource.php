<?php

namespace App\Filament\Resources\Rundowns;

use App\Filament\Resources\Rundowns\Pages\CreateRundown;
use App\Filament\Resources\Rundowns\Pages\EditRundown;
use App\Filament\Resources\Rundowns\Pages\ListRundowns;
use App\Filament\Resources\Rundowns\Schemas\RundownForm;
use App\Filament\Resources\Rundowns\Tables\RundownsTable;
use App\Models\Rundown;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RundownResource extends Resource
{
    protected static ?string $model = Rundown::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RundownForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RundownsTable::configure($table);
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
            'index' => ListRundowns::route('/'),
            'create' => CreateRundown::route('/create'),
            'edit' => EditRundown::route('/{record}/edit'),
        ];
    }
}
