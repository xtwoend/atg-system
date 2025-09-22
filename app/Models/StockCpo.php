<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCpo extends Model
{
    protected $fillable = ['atg_id', 'stock_date', 'stock_time', 'level', 'temp_avg', 'density', 'fk', 'volume', 'cpo', 'data_log'];
    
    protected $casts = [
        'stock_date' => 'date:Y-m-d',
        'data_log' => 'array'
    ];

    protected $appends = [
        'cpo_ton'
    ];

    public function getCpoTonAttribute()
    {
        return (float) ($this->cpo / 1000);
    }
}
