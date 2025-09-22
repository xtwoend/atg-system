<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientKey extends Model
{
    protected $fillable = [
        'name', 'key', 'secret', 'active'
    ]; 
}
