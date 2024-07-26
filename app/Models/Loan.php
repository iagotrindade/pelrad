<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'from',
        'to',
        'graduation',
        'name',
        'idt',
        'contact',
        'materials_info',
        'return_date',
        'file',
        'status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Nova cautela gerada';
                    break;

                case 'updated':
                    $eventName = 'Esta cautela foi alterada';
                    break;

                case 'restored':
                    $eventName = 'Esta cautela foi restaurada';
                    break;
                    
                case 'deleted':
                    $eventName = 'Esta cautela foi deletada';
                    break;
            }
            return $eventName;
        });
    }
}
