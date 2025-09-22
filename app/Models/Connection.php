<?php

namespace App\Models;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $fillable = [
        'name', 'host', 'port', 'username', 'password', 'status', 'error_message', 'last_connected'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class, 'connection_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
