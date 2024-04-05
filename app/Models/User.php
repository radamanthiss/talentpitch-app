<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'image_path',
        'password'
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
    public static function validateData(Request $request)
    {
        return $request->validate([
            'name' => 'required',
            'email' => 'required',
            'image_path' => 'required',
            'password' => 'required'
        ]);
    }
    public function challenges()
    {
        return $this->hasMany(Challenges::class);
    }

    public function companies()
    {
        return $this->hasMany(Companies::class);
    }

    public function programParticipants()
    {
        return $this->morphMany(ProgramParticipants::class, 'participantable');
    }
    public static function ValidatorRules(){
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'image_path' => 'required|string',
            'password' => 'required|string|min:8',
        ];
    }
}
