<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseInvoicesExport implements FromCollection, WithHeadings
{
    protected $expense_invoices_data;

    public function __construct($expense_invoices_data)
    {
        //dd($expense_invoices_data);
        $this->expense_invoices_data = $expense_invoices_data;
    }

    public function collection()
    {
        return collect($this->expense_invoices_data);
    }

    public function headings(): array
    {
        return [
            'id', 'business_unit_id', 'branch_id', 'project_id', 'invoice_no', 'invoice_date', 'total_amount', 'return_total_amount', 'description', 'upload_user_id', 'appoved_manager_id', 'manager_status', 'appoved_admin_id', 'admin_status', 'edit_by', 'created_at', 'updated_at', 'deleted_at'
        ];
    }

}
