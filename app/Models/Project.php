<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'prefix', 'description', 'deadline'];
    protected $withCount = ['teamMembers'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function epics()
    {
        return $this->hasMany(Epic::class);
    }

    public function teamLead()
    {
        return $this->belongsTo(TeamMember::class, 'team_lead_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(TeamMember::class, 'project_team_member', 'project_id', 'team_member_id');
    }
}
