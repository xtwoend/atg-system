<?php

namespace App\Models;

use App\Models\Connection;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'serial_number', 'manufacture', 'model', 'status', 'connection_id', 'topic', 'handler', 'last_connected', 'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function connection()
    {
        return $this->belongsTo(Connection::class, 'connection_id');
    }
}
