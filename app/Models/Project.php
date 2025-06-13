<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'prefix', 'description', 'deadline'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function epics()
    {
        return $this->hasMany(Epic::class);
    }
}
