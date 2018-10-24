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

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function memberships()
    {
        return $this->hasMany('App\RoomMembership');
    }
}
