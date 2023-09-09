<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExpenseInvoice;
use App\Models\IncomeInvoice;

class Dashboard extends Controller
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

  public function index()
  {
    $businessUnits = BusinessUnit::all();

    $income_inv = IncomeInvoice::where('admin_status', 'complete')->get();
    $expense_inv = ExpenseInvoice::where('admin_status', 'complete')->get();
    $total_users = User::get();
    $countdata['business_unit'] = count($businessUnits);
    $countdata['income_inv'] = count($income_inv);
    $countdata['expense_inv'] = count($expense_inv);
    $countdata['total_users'] = count($total_users);

    return view('cfms.admin.dashboard',compact('businessUnits','countdata'));
  }
}
