<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TicketResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketResource\RelationManagers;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->label('Project')
                    ->required()
                    ->searchable(),

                Select::make('epic_id')
                    ->relationship('epic', 'name')
                    ->label('Epic')
                    ->searchable()
                    ->nullable(),

                Select::make('ticket_status_id')
                    ->relationship('status', 'name')
                    ->label('Status')
                    ->required(),

                Select::make('assignee_id')
                    ->relationship('assignee', 'name')
                    ->label('Assignee')
                    ->searchable()
                    ->nullable(),

                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->nullable(),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->nullable()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('identifier')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('title')
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Project'),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('assignee.name')
                    ->label('Assignee')
                    ->default('-'),
                TextColumn::make('due_date')
                    ->label('Due')
                    ->date(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])
                    ->button()
                    ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
