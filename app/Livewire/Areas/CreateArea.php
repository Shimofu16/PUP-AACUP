<?php

namespace App\Livewire\Areas;

use App\Enums\AreaEnum;
use App\Models\Area;
use App\Models\User;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Html;
use Filament\Notifications\Notification;

class CreateArea extends Component implements HasForms
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

                Select::make('areas')
                    ->options(Area::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required()
                    ->searchable(),

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
            ->statePath('data')
            ->model(Area::class);
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

        foreach($data['areas'] as $area){
            $user->areas()->create(['area_id' => $area]);
        }

        foreach($data['programs'] as $program){
            $user->programs()->create(['program_id' => $program]);
        }

        Notification::make()
            ->title('Created successfully')
            ->body('Area has been created successfully.')
            ->success()
            ->send();

        //activity log
        activity()
            ->event('created')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['attributes' => $data])
            ->log('Assigned area and programs to user '. $user->name);

        $this->redirect(route('backend.areas.index'), true);
    }

    public function render(): View
    {
        return view('livewire.areas.create-area');
    }
}
