<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\IncomeInvoice;
use App\Models\IncomeInvoiceItem;
use App\Models\InvoiceNote;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\InvoiceDocument;
use App\Models\Branch;
use App\Models\BusinessUnit;
use Auth;
use DB;

class IncomeInvoiceController extends Controller
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

        $income_invoices = array();
        if($this->cuser_role == "Admin"){
            $income_invoices = IncomeInvoice::paginate(25);
        }elseif($this->cuser_role == "Manager"){
            if($this->cuser_business_unit_id){
                $income_invoices = IncomeInvoice::where('business_unit_id', $this->cuser_business_unit_id)->paginate(25);
            }
            
        }elseif($this->cuser_role == "Staff"){
            if($this->cuser_business_unit_id){
                $income_invoices = IncomeInvoice::where('business_unit_id', $this->cuser_business_unit_id)->where('upload_user_id', Auth::user()->id )->paginate(25);
            }
        }

        $data['user_role'] = $this->cuser_role;
        $data['business_unit_id'] = $this->cuser_business_unit_id;

        return view('cfms.income-invoice.index', compact('income_invoices','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->cuser_role = Auth::user()->user_role;
        $this->cuser_business_unit_id = Auth::user()->user_business_unit;

        if($this->cuser_role == "Admin"){
            return redirect('/income-invoice')->with('error', "Admin can't create invoice. Due to multiple business units!");
        }

        if($this->cuser_business_unit_id){
            return redirect('/income-invoice')->with('error', "Manager should has business unit!");
        }

        $itemcategories = ItemCategory::where('business_unit_id', $this->cuser_business_unit_id)->get();
        $statuses = $this->statuses;

        $businessUnits = BusinessUnit::where('id', $this->cuser_business_unit_id)->get();
        $branches = array();

        foreach ($businessUnits as $businessUnit) {
            $optgroupLabel = $businessUnit->name;
            $branchOptions = Branch::where('business_unit_id', $businessUnit->id)->pluck('name', 'id')->toArray();
            $branches[$optgroupLabel] = $branchOptions;
        }

        return view('cfms.income-invoice.create', compact('itemcategories','statuses','branches'));
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
            'branch_id'  =>  'required',
            'invoice_date'  =>  'required',
            'total_amount'  =>  'required'
        ]);

        $this->cuser_business_unit_id = Auth::user()->user_business_unit;

        //creating invoice no
        $latestInv = IncomeInvoice::orderBy('invoice_no','DESC')->first();
        if(isset($latestInv->invoice_no)){
            $invoice_no = str_pad($latestInv->invoice_no + 1, 10, "0", STR_PAD_LEFT);
        }else{
            $invoice_no = str_pad('0000000000' + 1, 10, "0", STR_PAD_LEFT);
        }

        $item_quantity = $request->quantity;
        $item_amount = $request->amount;

        $exp_invoice= IncomeInvoice::create([
                'business_unit_id' => isset($this->cuser_business_unit_id) ? $this->cuser_business_unit_id : 0,
                'branch_id' => $request->branch_id,
                'project_id' => ($request->branch_id) ? $request->branch_id : 0,
                'invoice_no' => $invoice_no,
                'invoice_date' => $request->invoice_date,
                'total_amount' => $request->total_amount,
                'return_total_amount' => $request->total_amount,
                'description' => $request->description,
                'upload_user_id' => Auth::id(),
                'appoved_manager_id' => 0,
                'manager_status' => 'processing',
                'appoved_admin_id' => 0,
                'admin_status' => 'processing',
                'edit_by' => Auth::id(),
            ]);

        if($request->hasfile('docs')) {

            $i=1;
            foreach($request->file('docs') as $file){

                $upload_path = 'income_docs/'.$exp_invoice->id;
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0775, true);  //create directory if not exist
                }

                $file_name = time(). '_'. $i . '.' . $file->getClientOriginalExtension();
                $org_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $file->move(public_path($upload_path), $file_name);

                InvoiceDocument::create([
                    'invoice_no' => 'INCINV-'.$exp_invoice->invoice_no,
                    'title' => $org_file_name,
                    'inv_file' => $upload_path.'/'.$file_name
                ]);

                $i++;
            }
            
        }
        return redirect('/income-invoice')->with('success', 'New Expense Invoice Added successfully.');

        // return redirect('/income-invoice')->with('error', 'Failed to add Expense Invoice!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = IncomeInvoice::find($id);
        $invoice_no = 'INCINV-'.$invoice->invoice_no;
        $invoice_items = IncomeInvoiceItem::where('invoice_id', $id)->get();
        $invoice_docs = InvoiceDocument::where('invoice_no', $invoice_no)->get();

        return view('cfms.income-invoice.view', compact('invoice', 'invoice_items','invoice_docs','invoice_no'));
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

        $itemcategories = ItemCategory::where('business_unit_id', $this->cuser_business_unit_id)->get();
        $statuses = $this->statuses;

        $businessUnits = BusinessUnit::where('id', $this->cuser_business_unit_id)->get();
        $branches = array();

        foreach ($businessUnits as $businessUnit) {
            $optgroupLabel = $businessUnit->name;
            $branchOptions = Branch::where('business_unit_id', $businessUnit->id)->pluck('name', 'id')->toArray();
            $branches[$optgroupLabel] = $branchOptions;
        }

        $invoice = IncomeInvoice::find($id);
        $invoice_no = 'INCINV-'.$invoice->invoice_no;
        $invoice_items = IncomeInvoiceItem::where('invoice_id', $id)->get();
        $invoice_docs = InvoiceDocument::where('invoice_no', $invoice_no)->get();

        return view('cfms.income-invoice.edit', compact('invoice', 'invoice_items','invoice_docs','invoice_no','itemcategories','branches', 'statuses'));
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
        // $request->validate([
        //     'branch_id'  =>  'required',
        //     'invoice_date'  =>  'required',
        //     'total_amount'  =>  'required'
        // ]);

        // $item_quantity = $request->quantity;
        // $item_amount = $request->amount;

        // $exp_invoice = IncomeInvoice::find($id);
        // $exp_invoice->branch_id = $request->branch_id;
        // $exp_invoice->project_id = ($request->project_id) ? $request->project_id : 0;
        // $exp_invoice->invoice_date = $request->invoice_date;
        // $exp_invoice->total_amount = $request->total_amount;
        // $exp_invoice->description = $request->description;
        // $exp_invoice->manager_status = $request->status;
        // $exp_invoice->admin_status = $request->status;
        // if(Auth::user()->role->name == "Admin"){
        //     $exp_invoice->appoved_admin_id = Auth::id();
        // }elseif(Auth::user()->role->name == "Manager") {
        //     $exp_invoice->appoved_manager_id = Auth::id();
        // }
        // $exp_invoice->edit_by = Auth::id();
        // $exp_invoice->save();

        // foreach($request->invitem as $itind => $item){

        //     $exp_invoice_item = IncomeInvoiceItem::find($item);
        //     $exp_invoice_item->qty = $item_quantity[$itind];
        //     $exp_invoice_item->amount = $item_amount[$itind];
        //     $exp_invoice_item->save();

        //     // $item_cate = Item::where('id',$item)->first();
        //     // IncomeInvoiceItem::create([
        //     //     'category_id' => $item_cate->category_id,
        //     //     'invoice_id' => $exp_invoice->id,
        //     //     'item_id' => $item,
        //     //     'qty' => $item_quantity[$itind],
        //     //     'amount' => $item_amount[$itind],
        //     // ]);
        // }

        // if($request->hasfile('docs')) {

        //     $i=1;
        //     foreach($request->file('docs') as $file){

        //         $upload_path = 'expense_docs/'.$exp_invoice->id;
        //         if (!file_exists($upload_path)) {
        //             mkdir($upload_path, 0775, true);  //create directory if not exist
        //         }

        //         $file_name = time(). '_'. $i . '.' . $file->getClientOriginalExtension();
        //         $org_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //         $file->move(public_path($upload_path), $file_name);

        //         InvoiceDocument::create([
        //             'invoice_no' => 'EXINV-'.$exp_invoice->invoice_no,
        //             'title' => $org_file_name,
        //             'inv_file' => $upload_path.'/'.$file_name
        //         ]);

        //         $i++;
        //     }

        // }
        // return redirect('/expense-invoice')->with('success', 'Income Invoice updated successfully.');

        // return redirect('/expense-invoice')->with('error', 'Failed to update Income Invoice!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exp_invoice = IncomeInvoice::find($id);
        $exp_invoice->delete();
        return redirect('/income-invoice')->with('success', 'Income Invoice deleted successfully.');
    }
}
