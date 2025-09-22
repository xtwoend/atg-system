<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtgDischarge extends Model
{
    protected $fillable = [
        'atg_id', 'started_at', 'ended_at', 'started_volume', 'ended_volume', 'sp_volume', 'sp_level', 'on_counter'
    ];

    protected $appends = [
        'volume'
    ];

    public function getVolumeAttribute()
    {
        return (float) $this->started_volume - $this->ended_volume;
    }
}
