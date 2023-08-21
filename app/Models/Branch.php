<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_unit_id',
        'name',
        'phone',
        'address',
    ];

    public function businessUnit(){
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }

    public function branchuser(){
        return $this->belongsTo(BranchUser::class, 'branch_id');
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('name','like',$term)
            ->orWhere('address','like',$term)
            ->orWhere('phone','like',$term)
            ->orWhereHas('businessUnit', function ($query) use ($term) {
                $query->where('name','like',$term);
            });
        });
    }
}
