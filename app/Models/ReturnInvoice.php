<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInvoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'total_amount',
        'description',
        'upload_user_id',
        'appoved_manager_id',
        'manager_status',
        'appoved_admin_id',
        'admin_status',
    ];
}
