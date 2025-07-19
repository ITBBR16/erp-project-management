<?php

namespace App\Filament\Resources\TicketResource\Pages;

use Filament\Actions;
use App\Models\Project;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TicketResource;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('select_project')
                ->label('Pilih Project')
                ->form([
                    Select::make('project_id')
                        ->label('Project')
                        ->options(Project::pluck('name', 'id'))
                        ->placeholder('Pilih project...')
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data, $livewire) {
                    $livewire->redirect(route('filament.admin.resources.tickets.index', ['project_id' => $data['project_id']]));
                })
                ->modalHeading('Pilih Project')
                ->modalSubmitActionLabel('Lihat Tiket'),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        if ($projectId = request()->query('project_id')) {
            $this->tableFilters = [
                'project_id' => ['value' => $projectId],
            ];
        }
    }
}
