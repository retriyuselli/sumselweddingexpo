<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Models\Expo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
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
                                            $label .= ' (' . $expo->periode . ')';
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

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Describe this photo...')
                            ->columnSpanFull(),

                        FileUpload::make('image_path')
                            ->label('Photos')
                            ->image()
                            ->disk('public')
                            ->directory('galleries')
                            ->visibility('public')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->maxSize(5120)
                            ->maxFiles(10)
                            ->required()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->helperText('Upload up to 10 photos (max 5MB each)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Additional Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('photographer_name')
                            ->label('Photographer')
                            ->maxLength(255)
                            ->placeholder('Photographer name'),

                        DatePicker::make('photo_date')
                            ->label('Photo Date')
                            ->native(false)
                            ->displayFormat('d M Y'),

                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first')
                            ->minValue(0),

                        TagsInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Add tags...')
                            ->helperText('Press enter to add tag'),
                    ]),

                Section::make('Publication Settings')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Featured Photo')
                            ->helperText('Show in featured gallery section')
                            ->inline(false)
                            ->default(false),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Make photo visible to public')
                            ->inline(false)
                            ->default(true),
                    ]),
            ]);
    }
}
