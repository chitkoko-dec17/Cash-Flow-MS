<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use App\Models\User;

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

    return view('cfms.admin.dashboard',compact('businessUnits'));
  }
}
