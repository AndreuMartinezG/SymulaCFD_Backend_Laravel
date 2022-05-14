<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'case',
        'default_Route_3D',
        'index_Route_3D',
        'category',
        'geometry_name'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function simulations()
    {
        return $this->hasMany(Simulation::class);
    }
}
