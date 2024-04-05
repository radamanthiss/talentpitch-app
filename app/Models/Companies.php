<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image_path', 'location', 'industry', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programParticipants()
    {
        return $this->morphMany(ProgramParticipants::class, 'participantable');

    }
    public static function ValidatorRules(){
        return [
            'name' => 'required|string|max:255',
            'image_path' => 'required|string',
            'location' => 'required|string',
            'industry' => 'required|string',
            'user_id' => 'required|integer'
        ];
    }
}
