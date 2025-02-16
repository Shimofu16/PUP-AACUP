<?php
namespace App\Livewire\Areas;

use App\Enums\AreaEnum;
use App\Models\Area;
use App\Models\User;
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
            ->model(Area::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (Area::where('area', $data['area'])->orWhere('user_id', $data['user_id'])->exists()) {
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

        Area::create($data);

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

