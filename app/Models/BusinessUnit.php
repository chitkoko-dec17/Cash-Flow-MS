<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'name',
        'bu_image',
        'phone',
        'address',
    ];
}
