<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_User extends Model
{
    use HasFactory;

    protected $table = 'role_users';

    protected $fillable = [
        'role_id',
        'user_id',
    ];

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
