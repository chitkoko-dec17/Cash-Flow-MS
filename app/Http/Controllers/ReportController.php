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
        return view('cfms.report.expense',compact('businessUnits','expense_invoices','data','expense_invoices_data'));
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
}
