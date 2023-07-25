<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
      $states = DB::table('items')->where('category_id',$request->cate_id)->get();
      return response()->json(['array_data'=>$states]);
    }
  }

  public function get_projects(Request $request)
  {
    if($request->ajax()){
      $projects = DB::table('projects')->where('branch_id',$request->branch_id)->get();
      return response()->json(['array_data'=>$projects]);
    }
  }
}
