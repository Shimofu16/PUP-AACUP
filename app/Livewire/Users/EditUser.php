<?php

namespace App\Livewire\Users;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class EditUser extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public User $record;

    public function mount(User $user): void
    {
        $this->record = $user;
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email', $this->record)
                    ->maxLength(255),
            ])
            ->columns(2)
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        activity()
            ->event('updated')
            ->causedBy(auth()->user())
            ->performedOn($this->record)
            ->log('Updated a user ' . $this->record->name);

        Notification::make()
            ->title('Saved successfully')
            ->body('User has been updated successfully.')
            ->success()
            ->send();

        $this->redirect(route('backend.users.index'), true);
    }

    public function render(): View
    {
        return view('livewire.users.edit-user');
    }
}
