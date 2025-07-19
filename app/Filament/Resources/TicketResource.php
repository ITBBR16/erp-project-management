<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
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
use Filament\Tables\Filters\SelectFilter;
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
                TextColumn::make('status.name')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('assignee.name')
                    ->label('Assignee')
                    ->default('-'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->getStateUsing(fn($record) => Carbon::parse($record->created_at)->format('d M Y')),
                TextColumn::make('due_date')
                    ->label('Due')
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(\App\Models\Project::pluck('name', 'id'))
                    ->hidden(),

                SelectFilter::make('epic_id')
                    ->label('Filter Epic')
                    ->options(function () {
                        $projectId = request()->input('tableFilters.project_id.value') ?? request()->query('project_id');
                        if (!$projectId) return [];

                        return \App\Models\Epic::where('project_id', $projectId)
                            ->pluck('name', 'id');
                    })
                    ->searchable(),

                SelectFilter::make('ticket_status_id')
                    ->label('Filter Status')
                    ->options(function () {
                        $projectId = request()->input('tableFilters.project_id.value') ?? request()->query('project_id');
                        if (!$projectId) return [];

                        return \App\Models\TicketStatus::whereHas('tickets', function ($q) use ($projectId) {
                            $q->where('project_id', $projectId);
                        })->pluck('name', 'id');
                    })
                    ->searchable(),
            ])
            ->modifyQueryUsing(function ($query) {
                $projectId = request()->input('tableFilters.project_id.value') ?? request()->query('project_id');

                if (!$projectId) {
                    return $query->whereRaw('1 = 0');
                }

                return $query
                    ->where('project_id', $projectId)
                    ->leftJoin('ticket_statuses', 'tickets.ticket_status_id', '=', 'ticket_statuses.id')
                    ->orderByRaw("
                        CASE
                            WHEN ticket_statuses.name = 'Done' THEN 1
                            ELSE 0
                        END
                    ")
                    ->orderBy('tickets.created_at', 'desc')
                    ->select('tickets.*');
            })
            ->emptyStateHeading('Tidak ada tiket')
            ->emptyStateDescription(fn() => request()->input('tableFilters.project_id.value')
                ? 'Tidak ditemukan tiket untuk project yang dipilih.'
                : 'Silakan pilih project terlebih dahulu.')
            ->emptyStateIcon('heroicon-o-document')
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
