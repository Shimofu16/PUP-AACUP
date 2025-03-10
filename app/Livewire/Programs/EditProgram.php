<?php

namespace App\Livewire\Programs;

use Filament\Forms;
use App\Models\Program;
use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class EditProgram extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Program $record;

    public function mount(Program $program): void
    {
        $this->record = $program;
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->directory(fn() => 'programs/' . Str::slug($this->data['name']))
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpanFull(),

            ])
            ->columns(2)
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);


        Notification::make()
        ->title('Saved successfully')
        ->body('Program has been updated successfully.')
        ->success()
        ->send();


        $this->redirect(route('backend.programs.index'), true);
    }


    public function render(): View
    {
        return view('livewire.programs.edit-program');
    }
}
