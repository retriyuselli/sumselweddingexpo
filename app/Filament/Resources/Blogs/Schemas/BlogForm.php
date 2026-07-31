<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Konten Blog')
                    ->description('Informasi utama artikel blog')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul Blog')
                            ->placeholder('Contoh: Tips Memilih Vendor Pernikahan')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->label('Slug URL')
                            ->placeholder('tips-memilih-vendor-pernikahan')
                            ->helperText('URL-friendly versi dari judul (otomatis dibuat)')
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Textarea::make('excerpt')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->label('Ringkasan')
                            ->placeholder('Ringkasan singkat artikel yang menarik pembaca...')
                            ->helperText('Maksimal 500 karakter')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->required()
                            ->label('Konten')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'link',
                                'blockquote',
                                'codeBlock',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Metadata & Kategori')
                    ->description('Informasi kategori dan pengaturan blog')
                    ->schema([
                        Select::make('blog_category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Kategori')
                            ->columnSpan(1),

                        ColorPicker::make('category_color')
                            ->required()
                            ->label('Warna Kategori')
                            ->default('#3b82f6')
                            ->helperText('Warna badge kategori')
                            ->columnSpan(1),

                        Select::make('user_id')
                            ->relationship('user', 'name', function ($query) {
                                return $query->whereHas('roles', function ($q) {
                                    $q->whereIn('name', ['super_admin', 'admin', 'author', 'editor']);
                                });
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Penulis')
                            ->helperText('Pilih user dengan role author/editor/admin')
                            ->columnSpan(1),

                        TextInput::make('read_time')
                            ->required()
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(60)
                            ->suffix('menit')
                            ->label('Waktu Baca')
                            ->helperText('Estimasi waktu baca dalam menit')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Media & Publikasi')
                    ->description('Upload gambar atau gunakan URL gambar eksternal')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->disk('public') // Pastikan menggunakan disk public
                            ->visibility('public')
                            ->directory('blog-images')
                            ->label('Upload Gambar')
                            ->helperText('Rekomendasi: 1200x600px')
                            ->columnSpanFull(),

                        DatePicker::make('date')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            ->label('Tanggal Publikasi')
                            ->columnSpan(1),

                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false)
                            ->helperText('Aktifkan untuk mempublikasikan artikel')
                            ->inline(false)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
