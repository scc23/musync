<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'spotify_id',
        'name',
        'avatar',
        'api_token',
        'refresh_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
    * A user can have many messages
    * Adding this in User for now. Will test later.
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }    
}
