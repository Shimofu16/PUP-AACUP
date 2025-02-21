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
    public $area = null;
    public $program_id = null;
    public $users = [];

    public function mount(): void
    {
        $this->form->fill();
        $this->updateUserOptions(); // Populate users initially
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
                    ->options(auth()->user()->hasRole('faculty') ? Program::whereIn('id', auth()->user()->area->program_ids)->pluck('name', 'id')->toArray() : Program::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->updateUserOptions($state, 'program_id')),

                Select::make('area')
                    ->options(auth()->user()->hasRole('faculty') ? auth()->user()->area->areas: AreaEnum::toArray())
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->updateUserOptions($state, 'area')),

                Select::make('user_id')
                    ->label('User')
                    ->default(auth()->user()->hasRole('faculty') ? auth()->user()->id : null)
                    ->options(fn() => $this->users)
                    ->searchable()
                    ->required()
                    ->disabled(auth()->user()->hasRole('faculty'))
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

        $this->redirect('/articles', true);
    }

    public function updateUserOptions($state = null, $field = null)
    {
        if ($field === 'program_id') {
            $this->program_id = $state;
        } elseif ($field === 'area') {
            $this->area = $state;
        }

        if (auth()->user()->hasRole('faculty')) {
            $this->users = User::where('id', auth()->user()->id)->pluck('name', 'id')->toArray();
            return;
        }

        $query = User::query()->role('faculty');

        if ($this->program_id) {
            $query->whereHas('area', function ($q) {
                $q->whereJsonContains('program_ids', $this->program_id);
            });

        }

        if ($this->area) {
            $query->whereHas('area', function ($q) {
                $q->whereJsonContains('areas', $this->area);
            });
        }

        $this->users = $query->pluck('name', 'id')->toArray();
    }

    public function render(): View
    {
        return view('livewire.articles.create-article');
    }
}
