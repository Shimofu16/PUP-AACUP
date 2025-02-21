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
                    ->options(AreaEnum::toArray())
                    ->multiple()
                    ->required()
                    ->searchable(),
                Select::make('program_ids')
                    ->label('Programs')
                    ->options(Program::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->label('User')
                    ->options(User::role('faculty')->pluck('name', 'id')->toArray())
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
        $data = $this->form->getState();

        // foreach ($data['area'] as $area) {
        //     // if (Area::where('area', $area)->where('user_id', $data['user_id'])->exists()) {
        //     //     $this->dispatch('swal', [
        //     //         'toast' => true,
        //     //         'position' => 'top-end',
        //     //         'showConfirmButton' => false,
        //     //         'timer' => 3000,
        //     //         'title' => 'Duplicate Entry!',
        //     //         'text' => 'This area or user already exists.',
        //     //         'icon' => 'error'
        //     //     ]);
        //     //     return;
        //     // }
        // }

        $area = Area::create([
            'areas' => $data['areas'],
            'program_ids' => $data['program_ids'],
            'user_id' => $data['user_id'],
        ]);

        $this->dispatch('swal', [
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false,
            'timer' => 3000,
            'title' => 'Success!',
            'text' => 'Area successfully created.',
            'icon' => 'success'
        ]);

        $this->redirect('/areas', true);
    }

    public function render(): View
    {
        return view('livewire.areas.create-area');
    }
}
