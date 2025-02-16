<?php

namespace App\Livewire\Areas;

use App\Enums\AreaEnum;
use App\Models\Area;
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
                Select::make('area')
                    ->options(AreaEnum::toArray())
                    ->required()
                    ->searchable(),

                Select::make('user_id')
                    ->label('User')
                    ->options(User::pluck('name', 'id')->toArray())
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
        $data = $this->form->getState();

        $query = Area::where(function ($query) use ($data) {
            $query->where('area', $data['area'])
                  ->orWhere('user_id', $data['user_id']);
        })->where('id', '!=', $this->record->id);

         if ($query->exists()){
            $this->dispatch('swal', [
                'toast' => true,
                'position' => 'top-end',
                'showConfirmButton' => false,
                'timer' => 3000,
                'title' => 'Duplicate Entry!',
                'text' => 'This area or user already exists.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->record->update($data);


        $this->dispatch('swal', [
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false,
            'timer' => 3000,
            'title' => 'Success!',
            'text' => 'Area successfully updated.',
            'icon' => 'success'
        ]);

        $this->redirect('/areas', true);
    }

    public function render(): View
    {
        return view('livewire.areas.edit-area');
    }
}
