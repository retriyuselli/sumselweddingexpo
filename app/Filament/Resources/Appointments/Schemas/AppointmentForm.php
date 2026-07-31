<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Models\Expo;
use App\Models\Vendor;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Appointment')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Data Utama')
                        ->schema([
                            Select::make('customer_id')
                                ->relationship('customer', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'customer')))
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->label('Customer')
                                ->required(),

                            Select::make('vendor_id')
                                ->relationship('vendor', 'nama_vendor')
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->label('Vendor')
                                ->required(),

                            Select::make('expo_id')
                                ->relationship('expo', 'nama_expo', modifyQueryUsing: fn (Builder $query) => $query->where('status', true))
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->label('Expo')
                                ->nullable(),

                            Select::make('status')
                                ->options([
                                    'requested' => 'Requested',
                                    'confirmed' => 'Confirmed',
                                    'rescheduled' => 'Rescheduled',
                                    'cancelled' => 'Cancelled',
                                    'completed' => 'Completed',
                                ])
                                ->label('Status')
                                ->default('requested')
                                ->required(),
                        ]),

                    Tab::make('Jadwal')
                        ->schema([
                            DateTimePicker::make('starts_at')
                                ->label('Mulai')
                                ->minDate(Carbon::now())
                                ->helperText('Tanggal dan waktu mulai janji temu')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $start = $get('starts_at');
                                    $end = $get('ends_at');
                                    if ($start && $end) {
                                        $set('duration_minutes', Carbon::parse($start)->diffInMinutes(Carbon::parse($end)));
                                    }
                                }),

                            DateTimePicker::make('ends_at')
                                ->label('Selesai')
                                ->required()
                                ->after('starts_at')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $start = $get('starts_at');
                                    $end = $get('ends_at');
                                    if ($start && $end) {
                                        $set('duration_minutes', Carbon::parse($start)->diffInMinutes(Carbon::parse($end)));
                                    }
                                }),

                            TextInput::make('duration_minutes')
                                ->numeric()
                                ->label('Durasi (menit)')
                                ->minValue(1)
                                ->readOnly()
                                ->nullable(),
                        ]),

                    Tab::make('Lokasi & Subjek')
                        ->schema([
                            Select::make('location_type')
                                ->options([
                                    'in_person' => 'Tatap muka',
                                    'online' => 'Online',
                                ])
                                ->label('Jenis Lokasi')
                                ->reactive()
                                ->required(),

                            TextInput::make('location_detail')
                                ->label('Detail Lokasi/Link')
                                ->placeholder('Alamat/booth atau link meeting')
                                ->helperText(fn (callable $get) => $get('location_type') === 'online' ? 'Masukkan link Zoom/Google Meet' : 'Masukkan alamat atau nomor booth')
                                ->required(fn (callable $get) => in_array($get('location_type'), ['in_person', 'online']))
                                ->maxLength(255)
                                ->nullable(),

                            TextInput::make('subject')
                                ->label('Subjek')
                                ->placeholder('Topik pertemuan')
                                ->required()
                                ->maxLength(255),
                        ]),

                    Tab::make('Kontak & Catatan')
                        ->schema([
                            Select::make('preferred_contact')
                                ->options([
                                    'whatsapp' => 'WhatsApp',
                                    'phone' => 'Telepon',
                                    'email' => 'Email',
                                ])
                                ->label('Kontak Preferensi')
                                ->native(false)
                                ->nullable(),

                            TextInput::make('contact_number')
                                ->label('Nomor Kontak')
                                ->placeholder('Nomor WhatsApp/Telepon atau biarkan kosong')
                                ->maxLength(255)
                                ->nullable(),

                            Textarea::make('notes')
                                ->label('Catatan')
                                ->placeholder('Detail kebutuhan, preferensi, atau informasi tambahan')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }
}
