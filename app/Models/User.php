<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'team_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function sentInvitations()
    {
        return $this->hasMany(ProjectInvitation::class, 'inviter_id');
    }

    // Связь с приглашениями, которые пользователь получил
    public function receivedInvitations()
    {
        return $this->hasMany(ProjectInvitation::class, 'invitee_id');
    }

    public function projectUser()
    {
        return $this->hasOne(ProjectUser::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function teamUser()
    {
        return $this->hasOne(TeamUser::class);
    }

    public function task()
    {
        return $this->hasMany(Task::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }
}
