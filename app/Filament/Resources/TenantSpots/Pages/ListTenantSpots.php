<?php

namespace App\Filament\Resources\TenantSpots\Pages;

use App\Filament\Resources\TenantSpots\TenantSpotResource;
use App\Models\Expo;
use App\Models\TenantSpot;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Section;

class ListTenantSpots extends ListRecords
{
    protected static string $resource = TenantSpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Generate batch booths from a template layout
            Action::make('generate')
                ->label('Generate Layout')
                ->icon('heroicon-o-squares-2x2')
                ->color('warning')
                ->schema([
                    Select::make('expo_id')
                        ->relationship('expo', 'nama_expo')
                        ->getOptionLabelFromRecordUsing(fn (Expo $record) => $record->nama_expo . ' (' . $record->periode . ')')
                        ->searchable()
                        ->required()
                        ->label('Expo'),

                    Section::make('Blok A')
                        ->columns(2)
                        ->schema([
                            TextInput::make('a_from')->label('Dari Nomor')->integer()->default(1)->minValue(1),
                            TextInput::make('a_to')->label('Sampai Nomor')->integer()->default(10)->minValue(1),
                        ]),

                    Section::make('Blok B Kiri')
                        ->columns(2)
                        ->schema([
                            TextInput::make('b_kiri_from')->label('Dari Nomor')->integer()->default(1)->minValue(1),
                            TextInput::make('b_kiri_to')->label('Sampai Nomor')->integer()->default(10)->minValue(1),
                        ]),

                    Section::make('Blok B Kanan')
                        ->columns(2)
                        ->schema([
                            TextInput::make('b_kanan_from')->label('Dari Nomor')->integer()->default(11)->minValue(1),
                            TextInput::make('b_kanan_to')->label('Sampai Nomor')->integer()->default(20)->minValue(1),
                        ]),

                    Section::make('Blok C')
                        ->columns(2)
                        ->schema([
                            TextInput::make('c_from')->label('Dari Nomor')->integer()->default(1)->minValue(1),
                            TextInput::make('c_to')->label('Sampai Nomor')->integer()->default(10)->minValue(1),
                        ]),
                ])
                ->action(function (array $data): void {
                    $expoId = $data['expo_id'];

                    // Blok A: 2 columns × N rows, fill column-first
                    TenantSpot::generateBatch($expoId, 'A', $data['a_from'], $data['a_to'], cols: 2, fillByCol: true);

                    // Blok B kiri: 5 columns × 2 rows
                    TenantSpot::generateBatch($expoId, 'B', $data['b_kiri_from'], $data['b_kiri_to'], cols: 5, section: 'kiri');

                    // Blok B kanan: 5 columns × 2 rows
                    TenantSpot::generateBatch($expoId, 'B', $data['b_kanan_from'], $data['b_kanan_to'], cols: 5, section: 'kanan');

                    // Blok C: single row
                    TenantSpot::generateBatch($expoId, 'C', $data['c_from'], $data['c_to'], cols: 10);

                    Notification::make()
                        ->title('Layout berhasil di-generate!')
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
