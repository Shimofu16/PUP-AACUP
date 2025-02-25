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
use Livewire\Component;
use Illuminate\Contracts\View\View;

class EditArea extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public Area $record;

    public function mount(Area $area): void
    {
        $this->record = $area;
        $this->form->fill($area->attributesToArray());
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
            ->model($this->record);
    }

    public function save(): void
    {

        // $query = Area::where(function ($query) use ($data) {
        //     $query->where('area', $data['area'])
        //         ->orWhere('user_id', $data['user_id']);
        // });

        // if ($query->exists()) {
        //     $this->dispatch('swal', [
        //         'toast' => true,
        //         'position' => 'top-end',
        //         'showConfirmButton' => false,
        //         'timer' => 3000,
        //         'title' => 'Duplicate Entry!',
        //         'text' => 'This area or user already exists.',
        //         'icon' => 'error'
        //     ]);
        //     return;
        // }

        Area::create($this->data);

        $this->dispatch('swal', [
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false,
            'timer' => 3000,
            'title' => 'Success!',
            'text' => 'Area successfully created.',
            'icon' => 'success'
        ]);

        $this->redirect(route('backend.areas.index'), true);
    }

    public function render(): View
    {
        return view('livewire.areas.create-area');
    }
}
