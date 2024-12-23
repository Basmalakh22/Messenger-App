<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    protected $appends = [
        'avatar-url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participants')
            ->latest('last_message_id')
            ->withPivot([
                'joined_at',
                'role'
            ]);
    }
    public function sentMessages()
    {
        return $this->hasMany(message::class, 'user_id', 'id');
    }
    public function receivedMessages()
    {
        return $this->belongsToMany(message::class, 'participants')->withPivot([
            'read_at',
            'deleted_at'
        ]);
    }
    public function getAvatarUrlAttribute()
    {
        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . $this->name;
    }
}
