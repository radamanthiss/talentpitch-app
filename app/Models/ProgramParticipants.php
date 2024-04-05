<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramParticipants extends Model
{
    use HasFactory;
    protected $fillable = ['program_id', 'entity_type', 'entity_id'];

    public function program()
    {
        return $this->belongsTo(Programs::class, 'program_id');
    }

    // Defines the polymorphic relationship.
    public function participantable()
    {
        return $this->morphTo();
    }
}
