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
        'shorten_code',
        'bu_image',
        'bu_letter_image',
        'phone',
        'address',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('name','like',$term)
            ->orWhere('address','like',$term)
            ->orWhere('phone','like',$term)
            ->orWhereHas('manager', function ($query) use ($term) {
                $query->where('name', 'like', $term)
                      ->whereHas('role', function ($query) {
                          $query->where('role_id', 2);
                      });
            });
        });
    }
}
