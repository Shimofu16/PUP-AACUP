<?php

namespace App\Livewire\ActivityLogs;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Checkbox;
use Spatie\Activitylog\Models\Activity;

class ListActivityLogs extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        // dd(Activity::query()->pluck('causer.name', 'causer.id')->toArray());
        return $table
            ->query(Activity::query())
            ->columns([
                TextColumn::make('causer.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('event')
                    ->badge()
                    ->color(fn(Activity $activity) => match ($activity->event) {
                        'created' => 'green',
                        'updated' => 'blue',
                        'deleted' => 'red',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                // TextColumn::make('subject.name')->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    // ->since()
                    // ->dateTimeTooltip()
                    ->searchable(),

            ])
            ->filters([
                //  events
                Filter::make('created')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->orWhere('event', 'created'))
                    ->label('created'),
                Filter::make('updated')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->orWhere('event', 'updated'))
                    ->label('updated'),
                Filter::make('deleted')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->orWhere('event', 'deleted'))
                    ->label('deleted'),

                // filter for date(created_at)
                Filter::make('created_at')
                    ->form([
                        Select::make('date')
                            ->options([
                                'today' => 'Today',
                                'yesterday' => 'Yesterday',
                                'this_week' => 'This Week',
                                'last_week' => 'Last Week',
                                'this_month' => 'This Month',
                                'last_month' => 'Last Month',
                                'this_year' => 'This Year',
                                'last_year' => 'Last Year',
                            ])
                            ->label('Date')
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['date'] ?? null) {
                            'today' => $query->whereDate('created_at', now()),
                            'yesterday' => $query->whereDate('created_at', now()->subDay()),
                            'this_week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                            'last_week' => $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]),
                            'this_month' => $query->whereMonth('created_at', now()->month),
                            'last_month' => $query->whereMonth('created_at', now()->subMonth()->month),
                            'this_year' => $query->whereYear('created_at', now()->year),
                            'last_year' => $query->whereYear('created_at', now()->subYear()->year),
                            default => $query,
                        };
                    }),
                    SelectFilter::make('user_id')
                    ->options(User::query()->pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereIn('causer_id', $data['values']);
                        }
                    })
                    ->label('User')
                    ->multiple()
                    ->searchable()
            ])
            ->filtersFormColumns(3)
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.activity-logs.list-activity-logs');
    }
}
