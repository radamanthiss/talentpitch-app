<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenges extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'difficulty', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programParticipants()
    {
        return $this->morphMany(ProgramParticipants::class, 'participantable');
    }
}
