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
use DB;

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

        $charts['expense_charts_item'] = $this->get_top_expense_items();
        $charts['expense_charts_cate'] = $this->get_top_expense_items_cate();
        // var_dump($charts);
        // exit;

        //Fetch list of results
        $expense_invoices = $queryExpInv->paginate(5);
        $this->expense_invoices_data = $queryExpInv->paginate(5);
        $data = $queryExpInv->get();
        // dd($this->export_data);
        return view('livewire.report',compact('businessUnits','statuses','expense_invoices','data','charts'));
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

    public function get_top_expense_items(){
        $expense_item_counts = array();
        $expense_item_names = array();
        $expense_items = DB::table('expense_invoices as exinv')
            ->leftJoin('expense_invoice_items as exinvi','exinv.id','=','exinvi.invoice_id')
            ->leftJoin('items as item','item.id','=','exinvi.item_id')
            ->selectRaw('item.name, COALESCE(sum(exinvi.qty),0) total')
            ->groupBy('exinvi.item_id')
            ->orderBy('total','desc')
            ->take(10)
            ->get();

        foreach($expense_items as $exp_item){
            $expense_item_counts[] = $exp_item->total;
            $expense_item_names[] = $exp_item->name;
        }
        $data['expense_item_counts'] = join(', ', $expense_item_counts);
        $data['expense_item_names'] = join(', ', $expense_item_names);

        // var_dump($data);exit;
        return $data;
    }

    public function get_top_expense_items_cate(){
        $expense_cate_counts = array();
        $expense_cate_names = array();

        $expense_items_cate = DB::table('expense_invoices as exinv')
            ->leftJoin('expense_invoice_items as exinvic','exinv.id','=','exinvic.invoice_id')
            ->leftJoin('item_categories as item_cate','item_cate.id','=','exinvic.category_id')
            ->selectRaw('item_cate.name, COALESCE(sum(exinvic.qty),0) total')
            ->groupBy('exinvic.category_id')
            ->orderBy('total','desc')
            ->take(10)
            ->get();

        foreach($expense_items_cate as $exp_item_cate){
            $expense_cate_counts[] = $exp_item_cate->total;
            $expense_cate_names[] = $exp_item_cate->name;
        }
        $data['expense_cate_counts'] = join(', ', $expense_cate_counts);
        $data['expense_cate_names'] = join(', ', $expense_cate_names);

        // var_dump($data);exit;
        return $data;
    }
}
