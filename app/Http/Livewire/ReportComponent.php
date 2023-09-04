<?php

namespace App\Http\Livewire;

use App\Exports\ExpenseInvoicesExport;
use App\Models\Branch;
use App\Models\BusinessUnit;
use App\Models\Project;
use App\Models\ExpenseInvoice;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ReportComponent extends Component
{
    use WithPagination;
    public $status,$project_id,$branch_id,$business_unit_id;
    public $selected_from_date,$selected_to_date,$selected_status,$selectedBuId,$selectedBranchId;
    public $branches = [];
    public $projects = [];

    protected $expense_invoices_data;
    protected $export_data = [];

    public function render()
    {
        $businessUnits = BusinessUnit::all();
        $statuses = array("pending" => "Pending","checking" => "Checking","checkedup" => "Checked Up","reject" => "Reject","complete" => "Complete");

        $queryExpInv = ExpenseInvoice::query();

        if($this->business_unit_id || $this->branch_id || $this->project_id || $this->selected_from_date || $this->selected_to_date || $this->status){

            if($this->business_unit_id) {
                $queryExpInv->where('business_unit_id', $this->business_unit_id);
            }

            if($this->branch_id) {
                $queryExpInv->where('branch_id', $this->branch_id);
            }

            if($this->project_id) {
                $queryExpInv->where('project_id', $this->project_id);
            }

            if($this->selected_from_date) {
                $queryExpInv->whereDate('invoice_date', '>=', $this->selected_from_date);
            }


            if($this->selected_to_date) {
                $queryExpInv->whereDate('invoice_date', '<=', $this->selected_to_date);
            }

            if($this->status) {
                $queryExpInv->Where('admin_status', $this->status);
            }
        }

        //Fetch list of results
        $expense_invoices = $queryExpInv->paginate(5);
        $this->expense_invoices_data = $queryExpInv->paginate(5);
        $data = $queryExpInv->get();
        //dd($this->export_data);
        return view('livewire.report',compact('businessUnits','statuses','expense_invoices','data'));
    }

    public function calculateTotal($inv)
    {
        return $inv->total_amount - $inv->return_total_amount;
    }

    public function calculateTotalSum()
    {
        if($this->expense_invoices_data){
            return $this->expense_invoices_data->sum(function ($inv) {
                return $this->calculateTotal($inv);
            });
        } else {
            return null;
        }
    }

    public function export($data)
    {
        $filename = 'expense_invoices_' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new ExpenseInvoicesExport($data), $filename);
    }

    public function updatedBusinessUnitId($value){
        if ($value) {
            // Fetch item_category based on the selected bu
            $this->branches = Branch::where('business_unit_id', $value)->get();
        } else {
            $this->branches = [];
        }
    }

    public function updatedBranchId($value){
        if ($value) {
            // Fetch item_category based on the selected bu
            $this->projects = Project::where('branch_id', $value)->get();
        } else {
            $this->projects = [];
        }
    }
}
