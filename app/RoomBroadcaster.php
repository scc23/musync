<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomBroadcaster extends Model
{
    protected $fillable = [
        'room_id',
        'user_id'
    ];

    protected $primaryKey = 'room_id';

    public $incrementing = false;

    public function room()
    {
        return $this->belongsTo('App\Room');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
