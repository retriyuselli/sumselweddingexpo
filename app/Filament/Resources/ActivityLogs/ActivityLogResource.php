<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $modelLabel = 'Activity Log';

    protected static ?string $pluralModelLabel = 'Activity Logs';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 99;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable()
                    ->timezone('Asia/Jakarta'),

                TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Aktivitas')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        str_contains(strtolower($state), 'created') => 'success',
                        str_contains(strtolower($state), 'updated') => 'warning',
                        str_contains(strtolower($state), 'deleted') => 'danger',
                        default => 'info',
                    }),

                TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn(?string $state): string => $state ? class_basename($state) : '-')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('subject_id')
                    ->label('ID')
                    ->default('-'),

                TextColumn::make('properties')
                    ->label('Perubahan')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '-';

                        $data = is_string($state) ? json_decode($state, true) : $state;

                        if (empty($data)) return '-';

                        $old = $data['old'] ?? [];
                        $new = $data['attributes'] ?? [];

                        if (empty($old) && empty($new)) {
                            return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        }

                        $changes = [];
                        foreach ($new as $key => $value) {
                            if (isset($old[$key]) && $old[$key] !== $value) {
                                $changes[] = "{$key}: {$old[$key]} → {$value}";
                            } elseif (!isset($old[$key])) {
                                $changes[] = "{$key}: {$value}";
                            }
                        }

                        return empty($changes) ? '-' : implode("\n", $changes);
                    })
                    ->wrap()
                    ->limit(100)
                    ->tooltip(function ($state) {
                        if (empty($state)) return null;
                        $data = is_string($state) ? json_decode($state, true) : $state;
                        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }),
            ])
            ->filters([
                SelectFilter::make('description')
                    ->label('Jenis Aktivitas')
                    ->options(
                        Activity::query()
                            ->distinct('description')
                            ->pluck('description', 'description')
                            ->toArray()
                    ),

                SelectFilter::make('subject_type')
                    ->label('Model')
                    ->options(
                        Activity::query()
                            ->whereNotNull('subject_type')
                            ->distinct('subject_type')
                            ->pluck('subject_type', 'subject_type')
                            ->mapWithKeys(fn($value) => [$value => class_basename($value)])
                            ->toArray()
                    ),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([25, 50, 100])
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
