<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'owner_id'
    ];

    public function owner()
    {
        return $this->hasOne(User::class, 'owner_id');
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'team_user');
    // }

    public function teamUsers()
    {
        return $this->hasMany(TeamUser::class);
    }

    public function projectUser()
    {
        return $this->belongsTo(ProjectUser::class);
    }

    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }
}
