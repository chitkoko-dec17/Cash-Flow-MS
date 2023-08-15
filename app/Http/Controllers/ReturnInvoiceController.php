<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\ReturnInvoice;
use App\Models\ExpenseInvoice;
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

        $return_invoices = array();
        if($this->cuser_role == "Admin"){
            $return_invoices = ReturnInvoice::paginate(25);
        }elseif($this->cuser_role == "Manager"){
            if($this->cuser_business_unit_id){
                $return_invoices = ReturnInvoice::where('business_unit_id', $this->cuser_business_unit_id)->paginate(25);
            }
            
        }elseif($this->cuser_role == "Staff"){
            if($this->cuser_business_unit_id){
                $return_invoices = ReturnInvoice::where('business_unit_id', $this->cuser_business_unit_id)->where('create_by', Auth::user()->id )->paginate(25);
            }
        }

        $data['user_role'] = $this->cuser_role;
        $data['business_unit_id'] = $this->cuser_business_unit_id;
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

        $file_name = time() . '.' . request()->docs->getClientOriginalExtension();

        $upload_path = 'return_docs/';

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
        $invoice = ExpenseInvoice::find($id);
        $invoice_no = 'EXINV-'.$invoice->invoice_no;
        $invoice_items = ExpenseInvoiceItem::where('invoice_id', $id)->get();
        $invoice_docs = InvoiceDocument::where('invoice_no', $invoice_no)->get();

        return view('cfms.return-invoice.view', compact('invoice', 'invoice_items','invoice_docs','invoice_no'));
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
        $return_inv->delete();
        return redirect('/return-invoice')->with('success', 'Return Invoice deleted successfully.');
    }
}
