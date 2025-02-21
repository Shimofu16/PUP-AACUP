<?php

namespace App\Livewire\Articles;

use App\Enums\AreaEnum;
use App\Models\Article;
use App\Models\Program;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Contracts\View\View;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListArticle extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = Auth::user(); 
        return $table
            ->query(Article::query()
                ->when($user->hasRole('faculty'), fn(Builder $query) => $query->where('user_id', $user->id)))
            ->columns([
                TextColumn::make('program.name')->searchable(),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('area')->searchable(),
                TextColumn::make('name')
                    ->label('Title')
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                ViewAction::make()
                    ->label('View Details')
                    ->modalHeading('Article Details')
                    ->modalDescription('View the details of this article.')
                    ->form([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->disabled(),

                                Select::make('program_id')
                                    ->label('Program')
                                    ->options(Program::pluck('name', 'id')->toArray())
                                    ->disabled(),
                                Select::make('area')
                                    ->options(AreaEnum::toArray())
                                    ->disabled(),

                                Select::make('user_id')
                                    ->label('User')
                                    ->options(User::pluck('name', 'id')->toArray())
                                    ->disabled(),


                                FileUpload::make('document')
                                    ->disabled(),
                                FileUpload::make('image')
                                    ->image()
                                    ->disabled(),
                                RichEditor::make('description')
                                    ->disabled()
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
                            ])->columns(2)
                    ]),
                EditAction::make()
                    ->url(fn(Article $record) => route('articles.edit', $record))
                    ->icon('heroicon-o-pencil')
                    ->label('Edit'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->action(fn(Article $record) => $record->delete())
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }


    public function render(): View
    {
        return view('livewire.articles.list-article');
    }
}
