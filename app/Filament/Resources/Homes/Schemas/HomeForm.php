<?php

namespace App\Filament\Resources\Homes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Penyelenggara')
                    ->schema([
                        Select::make('penyelenggara_id')
                            ->relationship('penyelenggara', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Penyelenggara')
                            ->native(false)
                            ->nullable(),
                    ]),
                Section::make('Tentang Kami')
                    ->schema([
                        RichEditor::make('tentang_kami')
                            ->label('Konten Tentang Kami')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Video Highlight')
                    ->schema([
                        Repeater::make('highlight_videos')
                            ->label('Video YouTube')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Video')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('video_id')
                                    ->label('YouTube Video ID')
                                    ->required()
                                    ->helperText('Contoh: SZtypoLHDu4 (dari https://youtu.be/SZtypoLHDu4)')
                                    ->columnSpan(1),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                Section::make('Meta & SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hero_subtitle')
                            ->label('Subtitle Hero')
                            ->columnSpan(1),

                        TextInput::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->columnSpan(1),
                    ]),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Halaman Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
