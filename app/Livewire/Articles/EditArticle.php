<?php

namespace App\Livewire\Articles;

use App\Enums\AreaEnum;
use Filament\Forms;
use App\Models\Article;
use App\Models\Program;
use App\Models\User;
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

class EditArticle extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Article $record;

    public $area = null;
    public $program_id = null;
    public $users = [];

    public function mount(Article $article): void
    {
        $this->record = $article;
        $this->updateUserOptions();
        $this->form->fill($article->attributesToArray());
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
                    ->options(auth()->user()->hasRole(['faculty']) ? Program::whereIn('id', auth()->user()->area->program_ids)->pluck('name', 'id')->toArray() : Program::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->updateUserOptions($state, 'program_id')),

                Select::make('area')
                    ->options(auth()->user()->hasRole(['faculty']) ? auth()->user()->area->areas : AreaEnum::toArray())
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->updateUserOptions($state, 'area')),

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
    public function updateUserOptions($state = null, $field = null)
    {
        if ($field === 'program_id') {
            $this->program_id = $state;
        } elseif ($field === 'area') {
            $this->area = $state;
        }

        if (auth()->user()->hasRole(['faculty'])) {
            $this->users = User::where('id', auth()->user()->id)->pluck('name', 'id')->toArray();
            return;
        }

        $query = User::query()->role(['faculty']);

        if ($this->program_id) {
            $query->whereHas('area', function ($q) {
                $q->whereJsonContains('program_ids', $this->program_id);
            });
        }

        if ($this->area) {
            // find the label of the area selected
            $area = AreaEnum::from($this->area + 1);
            $query->whereHas('area', function ($q) use ($area) {
                $q->whereJsonContains('areas', $area->label);
            });
        }

        $this->users = $query->pluck('name', 'id')->toArray();
    }
    public function save(): void
    {
        $data = $this->form->getState();
        $data['area'] = AreaEnum::from($data['area'] + 1)->label;
        $this->record->update($data);

        Notification::make()
            ->title('Saved successfully')
            ->body('Article has been updated successfully.')
            ->success()
            ->send();
        $this->redirect(route('backend.articles.index'), true);
    }

    public function render(): View
    {
        return view('livewire.articles.edit-article');
    }
}
