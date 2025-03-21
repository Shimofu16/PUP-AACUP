<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use App\Models\AreaUser;
use App\Models\User;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;

class ListArea extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        // dd(User::query()->with('areas')->first()->areas->pluck('area_id'));
        return $table
            ->query(User::query()->whereHas('areas')->with('areas'))
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('assignedAreas')
                    ->label('Assigned Areas')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->searchable(),
                TextColumn::make('assignedPrograms')
                    ->label('Assigned Programs')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->searchable(),
            ])
            ->filters([
                // SelectFilter::make('area')
                //     ->options(Area::pluck(
                //         'name',
                //         'id'
                //     ))
                //     ->label('Area')
                //     ->relationship('areas', 'area_id'),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn(User $record): string => route('backend.areas.edit', $record))
                    ->icon('heroicon-o-pencil')
                    ->label('Edit'),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->action(
                        function (User $record) {
                            //activity log
                            activity()
                                ->event('deleted')
                                ->causedBy(auth()->user())
                                ->performedOn($record)
                                ->log('Deleted assigned area for ' . $record->name);
                            $record->areas()->delete();
                            $record->programs()->delete();
                            Notification::make()
                                ->title('Deleted successfully')
                                ->body('Assigned Area has been deleted successfully.')
                                ->success()->send();
                        }
                    )
                    ->requiresConfirmation(),
            ])
            ->emptyStateHeading('No areas found')
            ->emptyStateDescription('No areas have been assigned to any users yet.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Asign area')
                    ->url(route('backend.areas.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.areas.list-area');
    }
}
