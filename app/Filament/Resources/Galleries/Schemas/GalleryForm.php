<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Models\Expo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Photo Information')
                    ->columns(2)
                    ->schema([
                        Select::make('expo_id')
                            ->label('Event / Expo')
                            ->options(function () {
                                return Expo::query()
                                    ->orderBy('tanggal_mulai', 'desc')
                                    ->get()
                                    ->mapWithKeys(function ($expo) {
                                        $label = $expo->nama_expo;
                                        if ($expo->periode) {
                                            $label .= ' ('.$expo->periode.')';
                                        }

                                        return [$expo->id => $label];
                                    });
                            })
                            ->searchable()
                            ->required()
                            ->preload()
                            ->columnSpanFull(),

                        TextInput::make('title')
                            ->label('Photo Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Grand Opening Ceremony')
                            ->columnSpanFull(),

                        FileUpload::make('image_path')
                            ->label('Photos')
                            ->image()
                            ->disk('public')
                            ->directory('galleries')
                            ->visibility('public')
                            ->multiple()
                            ->appendFiles()
                            ->reorderable()
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->maxSize(5120)
                            ->maxFiles(10)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->helperText('Upload hingga 10 foto sekaligus (maks 5MB per foto)')
                            ->columnSpanFull(),
                    ]),
                // Removed additional fields to simplify the form
            ]);
    }
}
