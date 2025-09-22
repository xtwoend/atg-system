<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolumeTable extends Model
{
    protected $fillable = [
        'atg_id', 'height', 'different', 'volume'
    ];

    public function atg()
    {
        return $this->belongsTo(Atg::class, 'atg_id');
    }
}
