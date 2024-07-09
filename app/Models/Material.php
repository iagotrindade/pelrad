<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'images',
        'name',
        'type_id',
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
        return $this->belongsTo(Type::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }
}
