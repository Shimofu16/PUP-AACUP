<?php

namespace App\Livewire\Programs;

use Filament\Tables;
use App\Models\Program;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ListProgram extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Program::query())
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Program Details')
                    ->modalDescription('View the details of this program.')
                    ->form([
                        Section::make()
                            ->schema([
                                FileUpload::make('image')->label('Image')->disabled()->columnSpanFull(),
                                TextInput::make('code')->label('Code')->disabled(),
                                TextInput::make('name')->label('Name')->disabled(),
                                RichEditor::make('description')->label('Description')->disabled()->columnSpanFull(),
                            ])
                            ->columns(2)

                    ]),
                EditAction::make()
                    ->url(fn(Program $record) => route('programs.edit', $record))
                    ->icon('heroicon-o-pencil')
                    ->label('Edit'),

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->action(fn(Program $record) => $record->delete())
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
        return view('livewire.programs.list-program');
    }
}
