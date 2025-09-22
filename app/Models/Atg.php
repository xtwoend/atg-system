<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Atg extends Model
{
    protected $fillable = [
        'name', 'location', 'capacity', 'factor_correction', 'temperature', 'svg_path', 'device_id', 'sounding_time'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function stock()
    {
        return AtgLog::table($this->id, Carbon::now()->format('Y-m-d'))->orderBy('terminal_time', 'desc')->first();
    }

    public function stocks()
    {
        return $this->hasMany(StockCpo::class, 'atg_id');
    }
}
