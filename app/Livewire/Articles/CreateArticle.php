<?php

namespace App\Livewire\Articles;

use App\Enums\AreaEnum;
use App\Models\Article;
use App\Models\Program;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class CreateArticle extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?string $area = "";

    public function mount($area = null): void
    {
        $this->area = $area;
        $this->form->fill([
            'area' => $area,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Select::make('program_id')
                    ->label('Program')
                    ->options(Program::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required()
                    ->preload(),
                Select::make('area')
                    ->options(AreaEnum::toArray())
                    ->required()
                    ->searchable()
                    ->default($this->area)
                    ->live() // Makes the field reactive
                    ->afterStateUpdated(fn($state) => $this->updateUserOptions($state)), // Update user list

                Select::make('user_id')
                    ->label('User')
                    ->options(
                        fn() =>
                        $this->area
                            ? User::whereHas('areas', function ($query) {
                                $query->where('area', $this->area);
                            })->pluck('name', 'id')->toArray()
                            : []
                    )
                    ->searchable()
                    ->required()
                    ->preload(),


                FileUpload::make('document')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->required(),
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
            ->model(Article::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

         Article::create($data);

         $this->dispatch('swal', [
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false,
            'timer' => 3000,
            'title' => 'Success!',
            'text' => 'Article successfully created.',
            'icon' => 'success'
        ]);

        $this->redirect('/areas', true);

    }
    public function updateUserOptions($selectedArea)
    {
        $this->area = $selectedArea;
        $this->data['user_id'] = null; 
    }

    public function render(): View
    {
        return view('livewire.articles.create-article');
    }
}
