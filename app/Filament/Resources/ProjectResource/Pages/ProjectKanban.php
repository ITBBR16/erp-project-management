<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Models\Project;
use App\Models\TicketStatus;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\ProjectResource;
use Filament\Support\Facades\FilamentAsset;

class ProjectKanban extends Page
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.project-kanban';
    protected static ?string $title = 'Project Board';


    public Project $record;

    public function mount(Project $record): void
    {
        $this->record = $record;
    }

    public function getTicketStatusesProperty()
    {
        return TicketStatus::orderBy('order')->get();
    }

    public function getViewData(): array
    {
        return [
            'ticketStatuses' => TicketStatus::with(['tickets' => function ($query) {
                $query->where('project_id', $this->record->id)->with('assignee');
            }])->orderBy('order')->get(),
        ];
    }
}
