<?php

namespace App\Livewire\Areas;

use App\Enums\AreaEnum;
use App\Models\Area;
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
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;

class ListArea extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Area::query())
            ->columns([
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('areas')
                    ->searchable(),
                TextColumn::make('programs')

                    ->searchable(),
        ])
            ->filters([
                SelectFilter::make('area')
                    ->options(AreaEnum::toArray())
                    ->label('Area')
            ])
            ->actions([
                EditAction::make()
                    ->url(fn(Area $record): string => route('backend.areas.edit', $record))
                    ->icon('heroicon-o-pencil')
                    ->label('Edit'),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->action(fn(Area $record) => $record->delete())
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
