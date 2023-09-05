<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseInvoicesExport;
use App\Exports\IncomeInvoicesExport;
use App\Models\BusinessUnit;
use App\Models\ExpenseInvoice;
use App\Models\IncomeInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportController extends Controller
{
    private $statuses = array("pending" => "Pending","checking" => "Checking","checkedup" => "Checked Up","reject" => "Reject","complete" => "Complete");
    private $chartFilters = array("quantity" => "Quantity" , "amount" => "Amount");
    protected $expense_data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function expense(Request $request)
    {
        $businessUnits = BusinessUnit::all();

        $selected_business_unit_id = ($request->business_unit_id) ? $request->business_unit_id : "";
        $selected_branch_id = ($request->branch_id) ? $request->branch_id : "";
        $selected_project_id = ($request->project_id) ? $request->project_id : "";
        $selected_from_date = ($request->selected_from_date) ? $request->selected_from_date : "";
        $selected_to_date = ($request->selected_to_date) ? $request->selected_to_date : "";
        $selected_status = ($request->status) ? $request->status : "";
        $selected_chartFilter = ($request->chartFilter) ? $request->chartFilter : "";

        $queryExpInv = ExpenseInvoice::query();

        if($selected_business_unit_id || $selected_branch_id || $selected_project_id || $selected_from_date || $selected_to_date || $selected_status || $selected_chartFilter){

            if($selected_business_unit_id) {
                $queryExpInv->where('business_unit_id', $selected_business_unit_id);
            }

            if($selected_branch_id) {
                $queryExpInv->where('branch_id', $selected_branch_id);
            }

            if($selected_project_id) {
                $queryExpInv->where('project_id', $selected_project_id);
            }

            if($selected_from_date) {
                $queryExpInv->whereDate('invoice_date', '>=', $selected_from_date);
            }


            if($selected_to_date) {
                $queryExpInv->whereDate('invoice_date', '<=', $selected_to_date);
            }

            if($selected_status) {
                $queryExpInv->Where('admin_status', $selected_status);
            }

            if($selected_chartFilter) {

            }
        }

        //Fetch list of results
        $expense_invoices = $queryExpInv->paginate(5);
        $expense_invoices_data['statuses'] = $this->statuses;
        $expense_invoices_data['chartFilters'] = $this->chartFilters;

        $expense_invoices_data['selected_business_unit_id'] = $selected_business_unit_id;
        $expense_invoices_data['selected_branch_id'] = $selected_branch_id;
        $expense_invoices_data['selected_project_id'] = $selected_project_id;
        $expense_invoices_data['selected_to_date'] = $selected_to_date;
        $expense_invoices_data['selected_from_date'] = $selected_from_date;
        $expense_invoices_data['selected_status'] = $selected_status;
        $expense_invoices_data['selected_chartFilter'] = $selected_chartFilter;

        $this->expense_data = $queryExpInv->paginate(5);

        $data = $queryExpInv->get();
        //dd($this->expense_data);

        $charts['expense_charts_item'] = $this->get_top_expense_items($request);
        $charts['expense_charts_cate'] = $this->get_top_expense_items_cate($request);

        return view('cfms.report.expense',compact('businessUnits','expense_invoices','data','expense_invoices_data', 'charts'));
    }

    public function income(Request $request)
    {
        $businessUnits = BusinessUnit::all();

        $selected_business_unit_id = ($request->business_unit_id) ? $request->business_unit_id : "";
        $selected_branch_id = ($request->branch_id) ? $request->branch_id : "";
        $selected_project_id = ($request->project_id) ? $request->project_id : "";
        $selected_from_date = ($request->selected_from_date) ? $request->selected_from_date : "";
        $selected_to_date = ($request->selected_to_date) ? $request->selected_to_date : "";
        $selected_status = ($request->status) ? $request->status : "";
        $selected_chartFilter = ($request->chartFilter) ? $request->chartFilter : "";

        $queryIncInv = IncomeInvoice::query();

        if($selected_business_unit_id || $selected_branch_id || $selected_project_id || $selected_from_date || $selected_to_date || $selected_status || $selected_chartFilter){

            if($selected_business_unit_id) {
                $queryIncInv->where('business_unit_id', $selected_business_unit_id);
            }

            if($selected_branch_id) {
                $queryIncInv->where('branch_id', $selected_branch_id);
            }

            if($selected_project_id) {
                $queryIncInv->where('project_id', $selected_project_id);
            }

            if($selected_from_date) {
                $queryIncInv->whereDate('invoice_date', '>=', $selected_from_date);
            }


            if($selected_to_date) {
                $queryIncInv->whereDate('invoice_date', '<=', $selected_to_date);
            }

            if($selected_status) {
                $queryIncInv->Where('admin_status', $selected_status);
            }

            if($selected_chartFilter) {

            }
        }

        //Fetch list of results
        $income_invoices = $queryIncInv->paginate(5);
        $income_invoices_data['statuses'] = $this->statuses;
        $income_invoices_data['chartFilters'] = $this->chartFilters;

        $income_invoices_data['selected_business_unit_id'] = $selected_business_unit_id;
        $income_invoices_data['selected_branch_id'] = $selected_branch_id;
        $income_invoices_data['selected_project_id'] = $selected_project_id;
        $income_invoices_data['selected_to_date'] = $selected_to_date;
        $income_invoices_data['selected_from_date'] = $selected_from_date;
        $income_invoices_data['selected_status'] = $selected_status;
        $income_invoices_data['selected_chartFilter'] = $selected_chartFilter;

        $this->expense_data = $queryIncInv->paginate(5);

        $data = $queryIncInv->get();
        //dd($this->expense_data);
        return view('cfms.report.income',compact('businessUnits','income_invoices','data','income_invoices_data'));
    }

    public function calculateTotal($inv)
    {
        return $inv->total_amount - $inv->return_total_amount;
    }

    public function calculateTotalSum()
    {
        if($this->expense_data){
            return $this->expense_data->sum(function ($inv) {
                return $this->calculateTotal($inv);
            });
        } else {
            return null;
        }
    }

    public function exportexpense($encodedData)
    {
        //dd($encodedData);
        $data = json_decode(urldecode($encodedData), true); // json to array
        $filename = 'report_expense_invoices_' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new ExpenseInvoicesExport($data), $filename);
    }

    public function exportincome($encodedData)
    {
        //dd($encodedData);
        $data = json_decode(urldecode($encodedData), true); // json to array
        $filename = 'report_income_invoices_' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new IncomeInvoicesExport($data), $filename);
    }

    public function budget(Request $request){
        return view('cfms.report.budget');
    }

    public function get_top_expense_items($filter){
        $selected_business_unit_id = ($filter->business_unit_id) ? $filter->business_unit_id : "";
        $selected_branch_id = ($filter->branch_id) ? $filter->branch_id : "";
        $selected_project_id = ($filter->project_id) ? $filter->project_id : "";
        $selected_from_date = ($filter->selected_from_date) ? $filter->selected_from_date : "";
        $selected_to_date = ($filter->selected_to_date) ? $filter->selected_to_date : "";
        $selected_status = ($filter->status) ? $filter->status : "";
        $selected_chartFilter = ($filter->chartFilter) ? $filter->chartFilter : "";

        $expense_item_counts = array();
        $expense_item_names = array();

        $query = DB::table('expense_invoices as exinv')
                ->leftJoin('expense_invoice_items as exinvi','exinv.id','=','exinvi.invoice_id')
                ->leftJoin('items as item','item.id','=','exinvi.item_id');

        if($selected_business_unit_id || $selected_branch_id || $selected_project_id || $selected_from_date || $selected_to_date || $selected_status || $selected_chartFilter){

            if($selected_business_unit_id) {
                $query->where('exinv.business_unit_id', $selected_business_unit_id);
            }

            if($selected_branch_id) {
                $query->where('exinv.branch_id', $selected_branch_id);
            }

            if($selected_project_id) {
                $query->where('exinv.project_id', $selected_project_id);
            }

            if($selected_from_date) {
                $query->whereDate('exinv.invoice_date', '>=', $selected_from_date);
            }

            if($selected_to_date) {
                $query->whereDate('exinv.invoice_date', '<=', $selected_to_date);
            }

            if($selected_status) {
                $query->Where('exinv.admin_status', $selected_status);
            }

            if($selected_chartFilter && $selected_chartFilter == "amount") {
                $query->selectRaw('item.name, COALESCE(sum(exinvi.amount),0) total');
            }else{
                $query->selectRaw('item.name, COALESCE(sum(exinvi.qty),0) total');
            }
        }else{
            $query->selectRaw('item.name, COALESCE(sum(exinvi.qty),0) total');
        }

        $expense_items = $query->orderBy('total','desc')->groupBy('exinvi.item_id')->take(10)->get();

        foreach($expense_items as $exp_item){
            $expense_item_counts[] = $exp_item->total;
            $expense_item_names[] = $exp_item->name;
        }
        $data['expense_item_counts'] = join(', ', $expense_item_counts);
        $data['expense_item_names'] = join(', ', $expense_item_names);

        // var_dump($data);exit;
        return $data;
    }

    public function get_top_expense_items_cate($filter){
        $selected_business_unit_id = ($filter->business_unit_id) ? $filter->business_unit_id : "";
        $selected_branch_id = ($filter->branch_id) ? $filter->branch_id : "";
        $selected_project_id = ($filter->project_id) ? $filter->project_id : "";
        $selected_from_date = ($filter->selected_from_date) ? $filter->selected_from_date : "";
        $selected_to_date = ($filter->selected_to_date) ? $filter->selected_to_date : "";
        $selected_status = ($filter->status) ? $filter->status : "";
        $selected_chartFilter = ($filter->chartFilter) ? $filter->chartFilter : "";

        $expense_cate_counts = array();
        $expense_cate_names = array();

        $query = DB::table('expense_invoices as exinv')
            ->leftJoin('expense_invoice_items as exinvic','exinv.id','=','exinvic.invoice_id')
            ->leftJoin('item_categories as item_cate','item_cate.id','=','exinvic.category_id');

        if($selected_business_unit_id || $selected_branch_id || $selected_project_id || $selected_from_date || $selected_to_date || $selected_status || $selected_chartFilter){

            if($selected_business_unit_id) {
                $query->where('exinv.business_unit_id', $selected_business_unit_id);
            }

            if($selected_branch_id) {
                $query->where('exinv.branch_id', $selected_branch_id);
            }

            if($selected_project_id) {
                $query->where('exinv.project_id', $selected_project_id);
            }

            if($selected_from_date) {
                $query->whereDate('exinv.invoice_date', '>=', $selected_from_date);
            }

            if($selected_to_date) {
                $query->whereDate('exinv.invoice_date', '<=', $selected_to_date);
            }

            if($selected_status) {
                $query->Where('exinv.admin_status', $selected_status);
            }

            if($selected_chartFilter && $selected_chartFilter == "amount") {
                $query->selectRaw('item_cate.name, COALESCE(sum(exinvic.amount),0) total');
            }else{
                $query->selectRaw('item_cate.name, COALESCE(sum(exinvic.qty),0) total');
            }
        }else{
            $query->selectRaw('item_cate.name, COALESCE(sum(exinvic.qty),0) total');
        }

        $expense_items_cate = $query->orderBy('total','desc')->groupBy('exinvic.category_id')->take(10)->get();

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
