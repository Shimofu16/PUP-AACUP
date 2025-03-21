<?php

namespace App\Livewire\ActivityLogs;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ListActivityLogs extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        // dd(Activity::all());
        return $table
            ->query(Activity::query())
            ->columns([
                TextColumn::make('causer.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('event')
                    ->badge()
                    ->color(fn (Activity $activity) => match ($activity->event) {
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
                //dates
                // SelectFilter::make('created_at')
                //     ->options([
                //         'today' => 'Today',
                //         'yesterday' => 'Yesterday',
                //         'this_week' => 'This Week',
                //         'last_week' => 'Last Week',
                //         'this_month' => 'This Month',
                //         'last_month' => 'Last Month',
                //         'this_year' => 'This Year',
                //         'last_year' => 'Last Year',
                //     ])
                //     ->label('Date'),
            ])
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
