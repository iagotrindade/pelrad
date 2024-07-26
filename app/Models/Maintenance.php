<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'materials',
        'old_info',
        'description',
        'status',
        'destiny',
        'file',
    ];

    protected $casts = [
        'materials' => 'array',
        'old_info' => 'array'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Nova manutenção gerada';
                    break;

                case 'updated':
                    $eventName = 'Esta manutenção foi alterada';
                    break;

                case 'restored':
                    $eventName = 'Esta manutenção foi restaurada';
                    break;
                    
                case 'deleted':
                    $eventName = 'Esta manutenção foi deletada';
                    break;
            }
            return $eventName;
        });
    }
}
