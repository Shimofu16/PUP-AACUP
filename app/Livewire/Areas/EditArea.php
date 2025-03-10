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
                    ->options(User::role(['faculty'])->pluck('name', 'id')->toArray())
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

        $this->data['areas'] = array_map(function ($area) {
            if (AreaEnum::from($area + 1)) {
                return AreaEnum::from($area + 1)->label;
            }
        }, $this->data['areas']);

        Area::create($this->data);

        Notification::make()
        ->title('Saved successfully')
        ->body('Area has been updated successfully.')
        ->success()
        ->send();

        $this->redirect(route('backend.areas.index'), true);
    }

    public function render(): View
    {
        return view('livewire.areas.create-area');
    }
}
