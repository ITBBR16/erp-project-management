<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Workspace';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Project Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('prefix')
                    ->label('Project Prefix')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(10),

                Select::make('team_lead_id')
                    ->label('Team Lead')
                    ->relationship('teamLead', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name)
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('teamMembers')
                    ->label('Team Members')
                    ->relationship('teamMembers', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name)
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('deadline')
                    ->label('Deadline')
                    ->required(),

                Textarea::make('description')
                    ->label('Description')
                    ->maxLength(1000)
                    ->rows(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Project Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('prefix')
                    ->label('Prefix')
                    ->sortable(),

                TextColumn::make('teamLead.user.name')
                    ->label('Team Lead')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teamMembers.user.name')
                    ->label('Team Members')
                    ->listWithLineBreaks()
                    ->limitList(1),
                // ->toggleable(),

                TextColumn::make('deadline')
                    ->date()
                    ->label('Deadline')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('kanban')
                        ->label('Kanban')
                        ->icon('heroicon-o-view-columns')
                        ->url(fn($record) => route('filament.admin.resources.projects.kanban', ['record' => $record])),
                ])
                    ->button()
                    ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'kanban' => Pages\ProjectKanban::route('/{record}/kanban'),
        ];
    }
}
