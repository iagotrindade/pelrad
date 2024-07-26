<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compliance extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'file'
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Novo pronto gerado';
                    break;
                    
                case 'deleted':
                    $eventName = 'Este pronto foi deletado';
                    break;
            }
            return $eventName;
        });
    }
}
