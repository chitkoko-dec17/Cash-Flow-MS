<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseInvoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_unit_id',
        'branch_id',
        'project_id',
        'invoice_no',
        'invoice_date',
        'total_amount',
        'return_total_amount',
        'description',
        'upload_user_id',
        'appoved_manager_id',
        'manager_status',
        'appoved_admin_id',
        'admin_status',
        'edit_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'appoved_admin_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edit_by');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'upload_user_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'appoved_manager_id');
    }

    public function inv_items()
    {
        return $this->hasMany(ExpenseInvoiceItem::class);
    }
}
