<?php

namespace App\Livewire\Areas;

use App\Enums\AreaEnum;
use App\Models\Area;
use App\Models\Program;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class EditArea extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public User $record;

    public function mount(User $user): void
    {
        $this->record = $user;
        $this->form->fill(
            [
                'areas' => $user->areas->pluck('area_id')->toArray(),
                'programs' => $user->programs->pluck('program_id')->toArray(),
                'user_id' => $user->id,
            ]
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('areas')
                    ->options(Area::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('programs')
                    ->label('Programs')
                    ->options(Program::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->label('User')
                    ->options(User::role(['faculty'])->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required()
                    ->preload(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {

        $this->validate([
            'data.areas' => 'required',
            'data.programs' => 'required',
            'data.user_id' => 'required',
        ]);

        $data = $this->form->getState();
        $user = User::find($data['user_id']);
        $user->areas()->delete();
        $user->programs()->delete();

        foreach($data['areas'] as $area){
            $user->areas()->create(['area_id' => $area]);
        }

        foreach($data['programs'] as $program){
            $user->programs()->create(['program_id' => $program]);
        }
        Notification::make()
            ->title('Saved successfully')
            ->body('Area has been updated successfully.')
            ->success()
            ->send();

        //activity log
        activity()
        ->event('updated')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['attributes' => $data])
            ->log('Updated assigned areas and programs for user '.$user->name);

        $this->redirect(route('backend.areas.index'), true);
    }

    public function render(): View
    {
        return view('livewire.areas.create-area');
    }
}
