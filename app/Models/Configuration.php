<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization',
        'organization_slug',
        'company',
        'squad',
        'squad_leader',
        'company_leader',
        'organization_s4',
    ];
}
