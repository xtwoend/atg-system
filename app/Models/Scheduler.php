<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Scheduler extends Model
{
    protected $fillable = [
        'name', 'handler', 'run_at', 'is_daily', 'active'
    ];

    public function getHourAttribute()
    {
        $date = Carbon::parse($this->run_at);
        return $date->format('H:i');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
