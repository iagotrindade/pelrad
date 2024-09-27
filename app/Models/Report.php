<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'file',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()  
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Novo relatório gerado';
                    break;
            }
            return $eventName;
        });
    }
}
