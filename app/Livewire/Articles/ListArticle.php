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

    public array|string $status;

    public function table(Table $table): Table
    {
        return $table
            ->query(Article::query()
            ->when(Auth::user()->hasRole('faculty'), fn(Builder $query) => $query->where('user_id', Auth::user()->id))
            ->when(is_array($this->status), fn(Builder $query) => $query->whereIn('status', $this->status))
            ->when(is_string($this->status), fn(Builder $query) => $query->where('status', $this->status))
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('program.name')->searchable(),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('area.name')->searchable(),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('status')
                //     ->options([
                //         'accepted' => 'Accepted',
                //         'declined' => 'Declined',
                //         'pending' => 'Pending',
                //     ])
                //     ->label('Status')
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Action::make('view')
                        ->label('View Details')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Article $record) => route('backend.articles.show', $record)),
                    EditAction::make()
                        ->url(fn(Article $record) => route('backend.articles.edit', $record))
                        ->icon('heroicon-o-pencil')
                        ->label('Edit')
                        ->visible(fn() => !Auth::user()->hasRole('committee_reviewer')),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->label('Delete')
                        ->action(function (Article $record) {
                            // Delete the folder
                            Storage::deleteDirectory('public/articles/' . $record->name);

                            activity()
                            ->event('deleted')
                                ->causedBy(auth()->user())
                                ->performedOn($record)
                                ->log('Deleted article ' . $record->name);

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
                        ->visible(fn(Article $record) => Auth::user()->hasRole('committee_reviewer') || $record->status === 'pending')
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
                       
                        ->action(function(Article $record)  {
                            activity()
                                ->event('updated')
                                ->causedBy(auth()->user())
                                ->performedOn($record)
                                ->log('Accepted article ' . $record->name);
                            // Accept the article
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
