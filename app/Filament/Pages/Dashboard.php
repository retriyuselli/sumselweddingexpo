<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DataPembayaranOverview;
use App\Filament\Widgets\PengeluaranOverview;
use App\Models\Expo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            DataPembayaranOverview::class,
            PengeluaranOverview::class,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('expo_id')
                            ->label('Pilih Expo')
                            ->options(Expo::pluck('nama_expo', 'id'))
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        DatePicker::make('startDate')
                            ->default(now()->startOfMonth()->toDateString())
                            ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                        DatePicker::make('endDate')
                            ->default(now()->endOfMonth()->toDateString())
                            ->minDate(fn (Get $get) => $get('startDate') ?: now())
                            ->maxDate(now()),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
