<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Component extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'code_number',
        'quantity',
        'serial_number',
        'material_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Novo componente criado';
                    break;

                case 'updated':
                    $eventName = 'Este componente foi alterado';
                    break;

                case 'restored':
                    $eventName = 'Este componente foi restaurado';
                    break;
                    
                case 'deleted':
                    $eventName = 'Este componente foi deletado';
                    break;
            }
            return $eventName;
        });
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'id', 'categories_id');
    }
}
