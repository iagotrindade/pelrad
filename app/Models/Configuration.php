<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuration extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'organization',
        'organization_slug',
        'company',
        'squad',
        'squad_leader',
        'company_leader',
        'organization_s4',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'As configurações das cautelas foram geradas pelo sistema';
                    break;

                case 'updated':
                    $eventName = 'As configurações das cautelas foram alteradas';
                    break;
            }
            return $eventName;
        });
    }
}
