<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

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
}
