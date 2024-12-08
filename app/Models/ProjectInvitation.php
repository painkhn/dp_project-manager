<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectInvitation extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectInvitationFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'inviter_id',
        'invitee_id',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    // Связь с пользователем, которому отправили приглашение
    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
