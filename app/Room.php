<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'id',
        'name',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $appends = [
        'isPrivate'
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function getIsPrivateAttribute()
    {
        return !empty($this->attributes['password']);
    }

    public function memberships()
    {
        return $this->hasMany('App\RoomMembership');
    }

    public function broadcaster()
    {
        return $this->hasOne('App\RoomBroadcaster');
    }
}
