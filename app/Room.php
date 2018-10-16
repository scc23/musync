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

    /**
     * Get the room's ID.
     *
     * @return string
     */
    public function getIdAttribute($value)
    {
        return ucfirst($value);
    }
}
