<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateBudget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'org_id',
        'business_unit_id',
        'branch_id',
        'project_id',
        'name',
        'total_amount',
        'start_date',
        'end_date',
    ];

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function org()
    {
        return $this->belongsTo(OrgStructure::class, 'org_id');
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('name','like',$term)
            ->orwhere('total_amount','like',$term)
            ->orwhere('start_date','like',$term)
            ->orwhere('end_date','like',$term)
            ->orWhereHas('businessUnit', function ($query) use ($term) {
                $query->where('name','like',$term);
            })
            ->orWhereHas('branch', function ($query) use ($term) {
                $query->where('name','like',$term);
            })
            ->orWhereHas('org', function ($query) use ($term) {
                $query->where('name','like',$term);
            })
            ->orWhereHas('project', function ($query) use ($term) {
                $query->where('name','like',$term);
            });
        });
    }
}
