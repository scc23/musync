<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'id',
        'name',
        'password',
        'playlist_id'
    ];

    protected $hidden = [
        'password'
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function memberships()
    {
        return $this->hasMany('App\RoomMembership');
    }
}
