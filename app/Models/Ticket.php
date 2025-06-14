<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'epic_id',
        'ticket_status_id',
        'assignee_id',
        'identifier',
        'title',
        'description',
        'due_date',
    ];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            if (!$ticket->identifier && $ticket->project_id) {
                $project = Project::find($ticket->project_id);
                $prefix = $project->prefix ?? 'TICKET';
                $count = Ticket::where('project_id', $ticket->project_id)->count() + 1;
                $ticket->identifier = $prefix . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function epic()
    {
        return $this->belongsTo(Epic::class);
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
