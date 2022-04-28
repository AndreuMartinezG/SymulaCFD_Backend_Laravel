<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'num_case',
        'project_status',
        'is_finished',

    ];

    public function projects()
    {
        return $this->belongsTo(Project::class);
    }
}
