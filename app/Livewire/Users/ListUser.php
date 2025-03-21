<?php

namespace App\Livewire\Users;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;

class ListUser extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role('faculty'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->url(fn(User $record) => route('backend.users.edit', $record))
                    ->icon('heroicon-o-pencil')
                    ->label('Edit'),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->action(function (User $record) {
                        activity()
                            ->event('deleted')
                            ->causedBy(auth()->user())
                            ->performedOn($record)
                            ->log('Deleted a user ' . $record->name);
                            
                        $record->delete();
                        Notification::make()
                            ->title('Deleted successfully')
                            ->body('User has been deleted successfully.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.users.list-user');
    }
}
