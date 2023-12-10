<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExpenseInvoice;
use App\Models\IncomeInvoice;
use Auth;

class Dashboard extends Controller
{
    private $cuser_role = null;
    private $cuser_business_unit_id = null;
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
    //$businessUnits = null;
    $this->cuser_role = Auth::user()->user_role;
    $this->cuser_business_unit_id = Auth::user()->user_business_unit;

    if($this->cuser_role == "Admin" || $this->cuser_role == "Account" || $this->cuser_role == "HR"){
        $businessUnits = BusinessUnit::all();
    } elseif ($this->cuser_role == "Manager"){
        $bu = BusinessUnit::find($this->cuser_business_unit_id);

        // Check if a BusinessUnit was found
        if ($bu) {
            $businessUnits = [$bu]; // Wrap the single BusinessUnit in an array
        } else {
            $businessUnits = []; // No BusinessUnit found
        }
    }elseif ($this->cuser_role == "Report"){
        return redirect('/report/expense');
    }else{
        return redirect('/expense-invoice');
    }

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
