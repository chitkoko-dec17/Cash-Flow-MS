<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnInvoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_unit_id',
        'invoice_id',
        'invoice_date',
        'total_amount',
        'description',
        'return_form_file',
        'create_by',
        'edit_by',
    ];

    public function expense_inv()
    {
        return $this->belongsTo(ExpenseInvoice::class, 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
}
