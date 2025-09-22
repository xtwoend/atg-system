<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'tag', 'value', 'value_type'
    ];
    
    public static function collection() {
        $tags = static::get()->pluck('value', 'tag')->toArray();
        return new Collection($tags);
    }
}
