<?php

namespace App\Livewire\Articles;

use Filament\Forms;
use App\Models\User;
use App\Enums\AreaEnum;
use App\Models\Area;
use App\Models\AreaParameter;
use App\Models\Article;
use App\Models\Program;
use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

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
                    ->options(auth()->user()->hasRole(['faculty']) ?  auth()->user()->programs()->pluck('program_id')->toArray() : Program::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->updateUserOptions($state, 'program_id')),

                Select::make('area_id')
                    ->label('Area')
                    ->options(auth()->user()->hasRole(['faculty']) ? auth()->user()->areas()->pluck('area_id')->toArray() : Area::pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->updateUserOptions($state, 'area_id')),

                Select::make('area_parameter_id')
                    ->label('Area Parameter')
                    ->options(fn() => $this->area ? AreaParameter::where('area_id', $this->area)->orderBy('name', 'asc')->pluck('name', 'id')->toArray() : [])
                    ->required()
                    ->searchable()
                    ->live(),

                Select::make('user_id')
                    ->label('User')
                    ->default(auth()->user()->hasRole(['faculty']) ? auth()->user()->id : null)
                    ->options(fn() => $this->users)
                    ->searchable()
                    ->required()
                    ->disabled(auth()->user()->hasRole(['faculty']))
                    ->preload(),

                FileUpload::make('document')
                    ->directory(fn() => 'articles/' . Str::slug($this->data['name']))
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),
                FileUpload::make('image')
                    ->directory(fn() => 'articles/' . Str::slug($this->data['name']))
                    ->image()
                    ->required(),
                FileUpload::make('video')
                    ->directory(fn() => 'articles/' . Str::slug($this->data['name']))
                    ->acceptedFileTypes(['video/mp4']),
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

    public function save(): void
    {
        $data = $this->form->getState();

        $article = Article::create($data);

        Notification::make()
        ->title('Saved successfully')
        ->body('Article has been created successfully.')
        ->success()
        ->send();

        activity()
            ->event('created')
            ->causedBy(auth()->user())
            ->performedOn($article)
            ->log('Created a new article '. $article->name);

        $this->redirect(route('backend.articles.index'), true);
    }

    public function updateUserOptions($state = null, $field = null)
    {
        if ($field === 'program_id') {
            $this->program_id = $state;
        } elseif ($field === 'area_id') {
            $this->area = $state;
        }
        // dd($this->area);
        if (auth()->user()->hasRole(['faculty'])) {
            $this->users = User::where('id', auth()->user()->id)->pluck('name', 'id')->toArray();
            return;
        }

        $query = User::query()->role(['faculty']);

        if ($this->program_id) {
            $query->whereHas('programs', function ($q) {
                $q->where('program_id', $this->program_id);
            });
        }

        if ($this->area) {
            $query->whereHas('areas', function ($q){
                $q->where('area_id', $this->area);
            });
        }

        $this->users = $query->pluck('name', 'id')->toArray();
    }

    public function render(): View
    {
        return view('livewire.articles.create-article');
    }
}
