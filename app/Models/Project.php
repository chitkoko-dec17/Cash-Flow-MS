<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'name',
        'phone',
        'address',
    ];

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('name','like',$term)
            ->orWhere('address','like',$term)
            ->orWhere('phone','like',$term)
            ->orWhereHas('branch', function ($query) use ($term) {
                $query->where('name','like',$term);
            });
        });
    }
}
