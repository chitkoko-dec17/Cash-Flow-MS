<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EstimateBudget;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class CommonController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function get_items(Request $request)
  {
    if($request->ajax()){
      $states = DB::table('items')->where('category_id',$request->cate_id)->where('invoice_type_id',$request->inv_type)->get();
      return response()->json(['array_data'=>$states]);
    }
  }

  public function get_itemById(Request $request){
    if($request->ajax()){
        $states = DB::table('items')->where('id',$request->id)->get();
        return response()->json(['array_data'=>$states]);
      }
  }

  public function get_itemCategoryById(Request $request){
    if($request->ajax()){
        $states = DB::table('item_categories')->where('id',$request->id)->get();
        return response()->json(['array_data'=>$states]);
      }
  }

  public function get_itemUnitById(Request $request){
    if($request->ajax()){
        $states = DB::table('item_units')->where('id',$request->id)->get();
        return response()->json(['array_data'=>$states]);
      }
  }

  public function get_branches(Request $request)
  {
    if($request->ajax()){
      $branches = DB::table('branches')->where('business_unit_id',$request->business_unit_id)->get();
      //dd($branches);
      return response()->json(['array_data'=>$branches]);
    }
  }

  public function get_projects(Request $request)
  {
    if($request->ajax()){
      $projects = DB::table('projects')->where('branch_id',$request->branch_id)->get();
      return response()->json(['array_data'=>$projects]);
    }
  }

  public function get_budgets(Request $request)
  {
    $businessUnitId = $request->input('business_unit_id');
    $branchId = $request->input('branch_id');
    $projectId = $request->input('project_id');

    if($request->ajax()){
        $budgetData = EstimateBudget::where(function ($query) use ($businessUnitId, $branchId, $projectId) {
            if ($businessUnitId) {
                $query->where('business_unit_id', $businessUnitId);
            }
            if ($branchId) {
                $query->where('branch_id', $branchId);
            }
            if ($projectId) {
                $query->where('project_id', $projectId);
            }
        })->get();

        return response()->json(['budget_data' => $budgetData]);
    }
  }

  public function get_expenseInvoiceById(Request $request){
    if($request->ajax()){
        $expenseInvoice = DB::table('expense_invoices')->where('id',$request->id)->get();
        return response()->json(['array_data'=>$expenseInvoice]);
      }
  }

  public function get_expenseInvoiceItems(Request $request){
    if($request->ajax()){
        $expenseInvoiceItems = DB::table('expense_invoice_items')->where('invoice_id',$request->id)->where('invoice_type','expense')->get();
        return response()->json(['array_data'=>$expenseInvoiceItems]);
    }
  }

  public function get_expenseInvoices(Request $request)
  {
    if($request->ajax()){
      $expenseInvoices = DB::table('expense_invoices')->where('business_unit_id',$request->business_unit_id)->take(6)->get();
      return response()->json(['array_data'=>$expenseInvoices]);
    }
  }

  public function get_incomeInvoices(Request $request)
  {
    if($request->ajax()){
      $ncomeInvoices = DB::table('income_invoices')->where('business_unit_id',$request->business_unit_id)->take(6)->get();
      return response()->json(['array_data'=>$ncomeInvoices]);
    }
  }
}
