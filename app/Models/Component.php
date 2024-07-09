<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_number',
        'quantity',
        'serial_number',
        'material_id',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
