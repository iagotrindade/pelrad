<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oracle extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'question',
        'answer'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()  
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Esta informação foi adicionada a base de conhecimento do Oráculo';
                    break;
    
                case 'updated':
                        $eventName = 'Esta informação foi alterada na base de conhecimento do Oráculo';
                        break;

                case 'restored':
                    $eventName = 'Esta informação foi restaurada na base de conhecimento do Oráculo';
                    break;
    
                case 'deleted':
                    $eventName = 'Esta informação foi deletada da base de conhecimento do Oráculo';
                    break;
            }
            return $eventName;
        });
    }
}
