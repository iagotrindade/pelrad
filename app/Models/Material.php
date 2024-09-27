<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'images',
        'name',
        'categories_id',
        'description',
        'status',
        'serial_number',
        'inclusion_date',
        'record_number',
        'patrimony_number',
        'patrimony_value',
        'inclusion_document',

    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([
            'name', 
            'description',
            'status',
            'serial_number',
            'inclusion_date',
            'record_number',
            'patrimony_number',
            'patrimony_value',
            'inclusion_document',
        ])
        ->logOnlyDirty()    
        ->setDescriptionForEvent(function(string $eventName) {
            switch ($eventName) {
                case 'created':
                    $eventName = 'Novo material criado';
                    break;
    
                case 'updated':
                        $eventName = 'Este material foi alterado';
                        break;

                case 'restored':
                    $eventName = 'Este material foi restaurado';
                    break;
    
                case 'deleted':
                    $eventName = 'Este material foi deletado';
                    break;
            }
            return $eventName;
        });
    }
}
