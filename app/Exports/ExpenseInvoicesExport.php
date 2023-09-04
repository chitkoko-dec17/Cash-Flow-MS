<?php

namespace App\Exports;

use App\Models\ExpenseInvoice;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseInvoicesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $expense_invoices_data;
    use Exportable;

    public function __construct($expense_invoices_data)
    {
        $this->expense_invoices_data = $expense_invoices_data;
    }

    public function query()
    {
        $idArray = [];
        foreach ($this->expense_invoices_data as $data) {
            $idArray[] = $data['id'];
        }
        $myString = implode(',', $idArray);
        return ExpenseInvoice::query()
            ->with([
                'businessUnit:id,name',
                'branch:id,name',
                'project:id,name',
                'staff:id,name',
                'manager:id,name',
                'admin:id,name',
                'editor:id,name',
            ])
            ->whereIn('id', explode(',', $myString));
    }

    public function headings(): array
    {
        return [
            'Business Unit', 'Branch', 'Project', 'Invoice No', 'Invoice Date', 'Total Amount', 'Return Total Amount',
            'Description', 'Upload User', 'Approved Manager', 'Manager Status', 'Approved Admin', 'Admin Status', 'Edit By'
        ];
    }

    public function map($row): array
    {
        return [
            $row->businessUnit->name ?? '',
            $row->branch->name ?? '',
            $row->project->name ?? '',
            $row->invoice_no ?? '',
            $row->invoice_date?? '',
            $row->total_amount?? '',
            $row->return_total_amount?? '',
            $row->description?? '',
            $row->staff->name ?? '',
            $row->manager->name ?? '',
            $row->manager_status?? '',
            $row->admin->name ?? '',
            $row->admin_status?? '',
            $row->editor->name ?? '',
        ];
    }
}
