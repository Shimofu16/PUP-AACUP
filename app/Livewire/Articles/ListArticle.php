<?php

namespace App\Livewire\Articles;

use App\Models\User;
use Filament\Tables;
use App\Enums\AreaEnum;
use App\Models\Article;
use App\Models\Program;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;

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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'pending' => 'Pending',
                    ])
                    ->label('Status')
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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
                        ->url(fn(Article $record) => route('backend.articles.edit', $record))
                        ->icon('heroicon-o-pencil')
                        ->label('Edit')
                        ->visible(fn() => !Auth::user()->hasRole('committee_reviewer')),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->label('Delete')
                        ->action(function (Article $record) {
                            // Delete attached files
                            if ($record->document) {
                                Storage::delete($record->document);
                            }
                            if ($record->image) {
                                Storage::delete($record->image);
                            }
                            // Delete the article
                            $record->delete();
                            Notification::make()
                                ->title('Deleted successfully')
                                ->body('Article has been deleted successfully.')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn() => !Auth::user()->hasRole('committee_reviewer')),
                    Action::make('accept')
                        ->label('Accept')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->color('success')
                        ->modalIcon('heroicon-m-information-circle')
                        ->modalIconColor('warning')
                        ->modalHeading('Accept Article')
                        ->modalSubmitActionLabel('Accept')
                        ->modalCancelAction(false)
                        ->extraModalFooterActions([
                            Action::make('decline')
                                ->requiresConfirmation()
                                ->color('danger')
                                ->form([
                                    Section::make()
                                        ->schema([
                                            RichEditor::make('reason')
                                                ->label('Reason for Decline')
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
                                        ->columns(1)
                                ])
                                ->action(function (Article $record) {
                                    // Decline the article
                                    $record->update(['status' => 'declined', 'reason' => $record->reason]);
                                    Notification::make()
                                        ->title('Declined successfully')
                                        ->body('Article has been declined successfully.')
                                        ->success()
                                        ->send();
                                }),
                        ])
                        ->visible(fn(Article $record) => Auth::user()->hasRole('committee_reviewer'))
                        ->action(function(Article $record)  {
                            $record->update(['status' => 'accepted']);
                            Notification::make()
                                ->title('Accepted successfully')
                                ->body('Article has been accepted successfully.')
                                ->success()
                                ->send();
                        }),


                ])
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
