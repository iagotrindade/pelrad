<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty()
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Esta categoria foi criada';
                    break;
    
                case 'updated':
                        $eventName = 'Esta categoria foi atualizada';
                        break;

                case 'updated':
                    $eventName = 'Esta categoria foi restaurada';
                    break;
    
                case 'deleted':
                    $eventName = 'Esta categoria foi deletada';
                    break;
            }
            return $eventName;
        });
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'categories_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }
}
