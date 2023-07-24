<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'invoice_type_id',
        'name',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function invoiceType()
    {
        return $this->belongsTo(InvoiceType::class,'invoice_type_id');
    }

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('name','like',$term)
            ->orWhereHas('category', function ($query) use ($term) {
                $query->where('name','like',$term)
                ->orWhereHas('businessUnit', function ($query) use ($term) {
                    $query->where('name','like',$term);
                });
            })
            ->orWhereHas('invoiceType', function ($query) use ($term) {
                $query->where('name','like',$term);
            });
        });
    }
}
