<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\ReturnInvoice;
use App\Models\ExpenseInvoice;
use App\Models\ExpenseInvoiceItem;
use Auth;
use DB;

class ReturnInvoiceController extends Controller
{
    private $statuses = array("pending" => "Pending","checking" => "Checking","checkedup" => "Checked Up","reject" => "Reject","complete" => "Complete");
    private $cuser_role = null;
    private $cuser_business_unit_id = null;
    /**
     * Check authentication in the constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->cuser_role = Auth::user()->user_role;
        $this->cuser_business_unit_id = Auth::user()->user_business_unit;

        $selected_invoice_no = ($request->invoice_no) ? $request->invoice_no : "";
        $selected_from_date = ($request->from_date) ? $request->from_date : "";
        $selected_to_date = ($request->to_date) ? $request->to_date : "";
        $selected_status = ($request->status) ? $request->status : "";

        $return_invoices = array();
        $queryExpInv = ReturnInvoice::query();
        if($this->cuser_role == "Admin"){

        }elseif($this->cuser_role == "Manager"){
            if($this->cuser_business_unit_id){
                $queryExpInv->where('business_unit_id', $this->cuser_business_unit_id);
            }else{
                return redirect('/return-invoice')->with('error', 'Manager should had one business unit!');
            }

        }elseif($this->cuser_role == "Staff"){
            if($this->cuser_business_unit_id){
                $queryExpInv->where('business_unit_id', $this->cuser_business_unit_id)->where('create_by', Auth::user()->id);
            }else{
                return redirect('/return-invoice')->with('error', 'Staff should had one business unit!');
            }
        }

        if($selected_invoice_no || $selected_from_date || $selected_to_date || $selected_status){

            // if($selected_invoice_no){
            //     $queryExpInv->where('invoice_no', 'like', '%' . $selected_invoice_no . '%');
            // }

            if($selected_from_date) {
                $queryExpInv->whereDate('invoice_date', '>=', $selected_from_date);
            }

            if($selected_to_date) {
                $queryExpInv->whereDate('invoice_date', '<=', $selected_to_date);
            }

            // if($selected_status) {
            //     $queryExpInv->Where('admin_status', $selected_status);
            // }
        }

        //Fetch list of results
        $return_invoices = $queryExpInv->orderBy('id','desc')->paginate(25);

        $data['user_role'] = $this->cuser_role;
        $data['business_unit_id'] = $this->cuser_business_unit_id;
        $data['statuses'] = $this->statuses;

        //filter selected data
        $data['selected_from_date'] = $selected_from_date;
        $data['selected_to_date'] = $selected_to_date;
        $data['selected_status'] = $selected_status;
        $data['selected_invoice_no'] = $selected_invoice_no;

        return view('cfms.return-invoice.index', compact('return_invoices','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->cuser_role = Auth::user()->user_role;
        $this->cuser_business_unit_id = Auth::user()->user_business_unit;
        $user_id = Auth::user()->id;

        if($this->cuser_role == "Manager" && !$this->cuser_business_unit_id){
            return redirect('/return-invoice')->with('error', "Manager should has business unit!");
        }

        $expense_invoices = array();
        if($this->cuser_role == "Admin"){
            $expense_invoices = ExpenseInvoice::all(['id', 'invoice_no']);
        }elseif($this->cuser_role == "Manager"){
            $expense_invoices = ExpenseInvoice::where('business_unit_id', $this->cuser_business_unit_id)->get(['id', 'invoice_no']);
        }elseif($this->cuser_role == "Staff"){
            $expense_invoices = ExpenseInvoice::where('upload_user_id', $user_id)->get(['id', 'invoice_no']);
        }

        $data['expense_inv_id'] = ($request->expense_inv) ? $request->expense_inv : "";
        $data['invoice_items'] = array();
        $data['invoice'] = array();
        if($request->expense_inv){
            $data['invoice'] = ExpenseInvoice::where('id', $request->expense_inv)->first();
            $data['invoice_items'] = ExpenseInvoiceItem::where('invoice_id', $request->expense_inv)->where('invoice_type','expense')->get();
        }

        return view('cfms.return-invoice.create', compact('expense_invoices','data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'  =>  'required',
            'invoice_date'  =>  'required',
            'total_amount'  =>  'required',
            'docs'   =>  'mimes:jpg,png,jpeg,pdf,xls|max:3072'
        ]);

        $expense_invoice = ExpenseInvoice::where('id', $request->invoice_id)->get();

        //update return total amout in expense table
        $exp_invoice = ExpenseInvoice::find($request->invoice_id);
        $exp_invoice->return_total_amount = $request->total_amount;
        $exp_invoice->save();

        $return_inv = new ReturnInvoice;
        $return_inv->business_unit_id = $expense_invoice[0]->business_unit_id;
        $return_inv->invoice_id = $request->invoice_id;
        $return_inv->invoice_date = $request->invoice_date;
        $return_inv->total_amount = $request->total_amount;
        $return_inv->description = $request->description;
        $return_inv->create_by = Auth::id();
        $return_inv->edit_by = Auth::id();

        if($request->hasfile('docs')) {
            $file_name = time() . '.' . request()->docs->getClientOriginalExtension();

            $upload_path = 'return_docs/';

            $file_name = time() . '.' . request()->docs->getClientOriginalExtension();
            request()->docs->move(public_path($upload_path), $file_name);

            $return_inv->return_form_file = $upload_path.$file_name;
        }

        $return_inv->save();


        $this->cuser_business_unit_id = Auth::user()->user_business_unit;


        return redirect('/return-invoice')->with('success', 'New Return Invoice Added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = ReturnInvoice::find($id);
        // $invoice_no = 'EXINV-'.$invoice->invoice_no;

        return view('cfms.return-invoice.view', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->cuser_business_unit_id = Auth::user()->user_business_unit;
        $this->cuser_role = Auth::user()->user_role;
        $invoice = ReturnInvoice::find($id);

        $expense_invoices = array();
        if($this->cuser_role == "Admin"){
            $expense_invoices = ExpenseInvoice::all(['id', 'invoice_no']);
        }elseif($this->cuser_role == "Manager"){
            $expense_invoices = ExpenseInvoice::where('business_unit_id', $this->cuser_business_unit_id)->get(['id', 'invoice_no']);
        }elseif($this->cuser_role == "Staff"){
            $expense_invoices = ExpenseInvoice::where('business_unit_id', $this->cuser_business_unit_id)->where('upload_user_id', Auth::id())->get(['id', 'invoice_no']);
        }

        return view('cfms.return-invoice.edit', compact('invoice','expense_invoices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_id'  =>  'required',
            'invoice_date'  =>  'required',
            'total_amount'  =>  'required',
            'docs'   =>  'mimes:jpg,png,jpeg,pdf,xls|max:3072'
        ]);

        $upload_path = 'return_docs/';

        $exp_invoice = ExpenseInvoice::find($request->invoice_id);
        $exp_invoice->return_total_amount = $request->total_amount;
        $exp_invoice->save();

        $return_inv = ReturnInvoice::find($id);
        $return_inv->invoice_id = $request->invoice_id;
        $return_inv->invoice_date = $request->invoice_date;
        $return_inv->total_amount = $request->total_amount;
        $return_inv->description = $request->description;
        $return_inv->edit_by = Auth::id();

        if($request->hasfile('docs')) {
            $old_inv_file = $return_inv->return_form_file;

            if(file_exists(public_path($old_inv_file))){
                unlink(public_path($old_inv_file));
            }

            $file_name = time() . '.' . request()->docs->getClientOriginalExtension();
            request()->docs->move(public_path($upload_path), $file_name);

            $return_inv->return_form_file = $upload_path.$file_name;
        }

        $return_inv->save();
        
        return redirect('/return-invoice')->with('success', 'Return Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $return_inv = ReturnInvoice::find($id);

        //resetting the default value to expense table
        $exp_invoice = ExpenseInvoice::find($return_inv->invoice_id);
        $exp_invoice->return_total_amount = 0;
        $exp_invoice->save();

        //deleting the file
        $old_inv_file = $return_inv->return_form_file;

        if(file_exists(public_path($old_inv_file))){
            unlink(public_path($old_inv_file));
        }

        $return_inv->delete();
        return redirect('/return-invoice')->with('success', 'Return Invoice deleted successfully.');
    }
}
