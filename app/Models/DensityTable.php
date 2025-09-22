<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DensityTable extends Model
{
    protected $fillable = [
        'atg_id', 'temperature', 'density', 'fk'
    ];

    public function atg()
    {
        return $this->belongsTo(Atg::class, 'atg_id');
    }
}
