<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Network extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'frequency',
        'alternative_frequency',
        'stations_data',
        'file'
    ];

    protected $casts = [
        'stations_data' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()  
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Esta rede foi criada';
                    break;
    
                case 'updated':
                        $eventName = 'Esta rede foi alterada';
                        break;

                case 'restored':
                    $eventName = 'Esta rede foi restaurada';
                    break;
    
                case 'deleted':
                    $eventName = 'Esta rede foi deletada';
                    break;
            }
            return $eventName;
        });
    }
}
