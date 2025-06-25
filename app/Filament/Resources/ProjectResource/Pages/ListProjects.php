<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProjectResource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action as TableAction;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make(),
            TableAction::make('kanban')
                ->label('Kanban')
                ->url(fn($record) => ProjectResource::getUrl('kanban', ['record' => $record]))
                ->icon('heroicon-o-view-columns')
                ->color('info'),
        ];
    }
}
