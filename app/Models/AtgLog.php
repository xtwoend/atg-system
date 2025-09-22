<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

final class AtgLog extends Model
{
    protected $table = 'atg_log';
    protected $fillable = [
        'atg_id', 'terminal_time', 'level', 'percentage', 'temp_avg', 'temp_1', 'temp_2', 'temp_3', 'temp_4', 'temp_5', 'temp_6', 'temp_7', 'temp_8', 'temp_9', 'temp_10', 'temp_11', 'temp_12', 'temp_13', 'temp_14', 'volume', 'cpo', 'density', 'fk', 'pump_discharge'
    ];
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $appends = [
        'cpo_ton'
    ];

    public function atg()
    {
        return $this->belongsTo(Atg::class, 'atg_id');
    }

    protected static function booted(): void
    {
        self::creating(static function (AtgLog $model): void {
            $model->id = Str::uuid()->toString();
        });
    }

    public function getCpoTonAttribute()
    {
        return (float) round(($this->cpo / 1000), 2);
    }
    
    public static function table($id, $date = null)
    {
        $period = $date? Carbon::parse($date)->format('Ym'): Carbon::now()->format('Ym');
        
        $model = new self;
        $tableName = $model->getTable() . "_{$id}_{$period}";

        if(! Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->unsignedBigInteger('atg_id')->index();
                $table->datetime('terminal_time')->index();

                $table->double('level', 10, 2)->default(0);
                $table->double('percentage', 10, 2)->default(0);
                $table->double('temp_avg', 10, 2)->default(0);
                $table->double('temp_1', 10, 2)->default(0);
                $table->double('temp_2', 10, 2)->default(0);
                $table->double('temp_3', 10, 2)->default(0);
                $table->double('temp_4', 10, 2)->default(0);
                $table->double('temp_5', 10, 2)->default(0);
                $table->double('temp_6', 10, 2)->default(0);
                $table->double('temp_7', 10, 2)->default(0);
                $table->double('temp_8', 10, 2)->default(0);
                $table->double('temp_9', 10, 2)->default(0);
                $table->double('temp_10', 10, 2)->default(0);
                $table->double('temp_11', 10, 2)->default(0);
                $table->double('temp_12', 10, 2)->default(0);
                $table->double('temp_13', 10, 2)->default(0);
                $table->double('temp_14', 10, 2)->default(0);

                $table->double('volume', 13, 2)->default(0);
                $table->double('cpo', 13, 2)->default(0);
                $table->double('density', 10, 8)->default(0);
                $table->double('fk', 10, 8)->default(0);

                // pump discharge status
                $table->boolean('pump_discharge')->default(false);

                $table->timestamps();
            });
        }

        return $model->setTable($tableName);
    }
}
