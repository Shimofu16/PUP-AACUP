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

class CreateUser extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
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
                    ->unique(User::class, 'email')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ])
            ->columns(2)
            ->statePath('data')
            ->model(User::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);
        $record->markEmailAsVerified();
        $record->assignRole('faculty');

        activity()
            ->event('created')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->log('Created a user '. $record->name);

        Notification::make()
            ->title('Created successfully')
            ->body('User has been created successfully.')
            ->success()
            ->send();

        $this->redirect(route('backend.users.index'), true);
    }

    public function render(): View
    {
        return view('livewire.users.create-user');
    }
}
