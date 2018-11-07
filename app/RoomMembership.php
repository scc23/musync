<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomMembership extends Model
{
    protected $fillable = [
        'room_id',
        'user_id'
    ];

    public function room()
    {
        return $this->belongsTo('App\Room');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
