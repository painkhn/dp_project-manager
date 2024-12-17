<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectUserFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
    ];

    // Связь с проектом
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Связь с пользователем
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
