<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\ExpenseInvoice;
use App\Models\ExpenseInvoiceItem;
use App\Models\InvoiceNote;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\InvoiceDocument;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\ProjectUser;
use App\Models\BusinessUnit;
use App\Models\ItemUnit;
use Auth;
use DB;

class ExpenseInvoiceController extends Controller
{
    private $statuses = array("pending" => "Pending","checking" => "Checking","checkedup" => "Checked Up","reject" => "Reject","complete" => "Complete", "ready_to_claim" => "Ready To Claim","claimed" => "Claimed");
    private $hr_statuses = array("pending" => "Pending","checking" => "Checking","checkedup" => "Checked Up");
    private $manager_statuses = array("pending" => "Pending","checking" => "Checking","checkedup" => "Checked Up","reject" => "Reject");
    private $account_statuses = array("ready_to_claim" => "Ready To Claim","claimed" => "Claimed");
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

        $expense_invoices = array();
        $queryExpInv = ExpenseInvoice::query();
        if($this->cuser_role == "Admin"){

        }elseif($this->cuser_role == "Manager"){
            if($this->cuser_business_unit_id){
                // else{
                //     $expense_invoices = ExpenseInvoice::where('business_unit_id', $this->cuser_business_unit_id)->paginate(25);
                // }

                $queryExpInv->where('business_unit_id', $this->cuser_business_unit_id);

            }else{
                return redirect('/expense-invoice')->with('error', 'Manager should had one business unit!');
            }

        }elseif($this->cuser_role == "Staff"){
            if($this->cuser_business_unit_id){
                // $expense_invoices = ExpenseInvoice::where('business_unit_id', $this->cuser_business_unit_id)->where('upload_user_id', Auth::user()->id )->paginate(25);

                $queryExpInv->where('business_unit_id', $this->cuser_business_unit_id)->where('upload_user_id', Auth::user()->id);
            }
            else{
                return redirect('/expense-invoice')->with('error', 'Staff should had one business unit!');
            }
        }

        if($selected_invoice_no || $selected_from_date || $selected_to_date || $selected_status){

            // $queryExpInv = ExpenseInvoice::query();
            // $queryExpInv->where('business_unit_id', $this->cuser_business_unit_id);

            if($selected_invoice_no){
                $queryExpInv->where('invoice_no', 'like', '%' . $selected_invoice_no . '%');
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
        }

        //Fetch list of results
        $expense_invoices = $queryExpInv->orderBy('id','desc')->paginate(25);

        $data['user_role'] = $this->cuser_role;
        $data['business_unit_id'] = $this->cuser_business_unit_id;
        $data['statuses'] = $this->statuses;

        //filter selected data
        $data['selected_from_date'] = $selected_from_date;
        $data['selected_to_date'] = $selected_to_date;
        $data['selected_status'] = $selected_status;
        $data['selected_invoice_no'] = $selected_invoice_no;

        return view('cfms.expense-invoice.index', compact('expense_invoices','data'));
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
        $data['user_role'] = Auth::user()->user_role;

        // if($this->cuser_role == "Admin"){
        //     return redirect('/expense-invoice')->with('error', "Admin can't create invoice. Due to multiple business units!");
        // }

        if($this->cuser_role == "Manager" && !$this->cuser_business_unit_id){
            return redirect('/expense-invoice')->with('error', "Manager should has business unit!");
        }

        $itemcategories = ItemCategory::where('business_unit_id', $this->cuser_business_unit_id)->get();
        $itemunits = ItemUnit::get();
        // $items = Item::where('invoice_type_id', 0)->get();
        $statuses = $this->statuses;

        $businessUnits = BusinessUnit::where('id', $this->cuser_business_unit_id)->get();
        $branches = array();

        if($this->cuser_role != "Staff"){
            foreach ($businessUnits as $businessUnit) {
                $optgroupLabel = $businessUnit->name;

                $branchOptions = Branch::where('business_unit_id', $businessUnit->id)->pluck('name', 'id')->toArray();

                $branches[$optgroupLabel] = $branchOptions;
            }
        }
        if($this->cuser_role == "Staff"){

            $branch_user = BranchUser::where('user_id', Auth::id())->first();
            $project_user = ProjectUser::where('user_id', Auth::id())->first();
            $data['branch_id'] = $branch_user->branch_id;
            $data['project_id'] = isset($project_user->project_id) ? $project_user->project_id : 0;
        }
        // var_dump($data);exit;

        return view('cfms.expense-invoice.create', compact('itemcategories','statuses','branches', 'data','itemunits'));
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
        $latestInv = ExpenseInvoice::orderBy('invoice_no','DESC')->first();
        if(isset($latestInv->invoice_no)){
            $invoice_no = str_pad($latestInv->invoice_no + 1, 10, "0", STR_PAD_LEFT);
        }else{
            $invoice_no = str_pad('0000000000' + 1, 10, "0", STR_PAD_LEFT);
        }

        $item_quantity = $request->quantity;
        $item_amount = $request->amount;
        $item_unit_ids = $request->unit_ids;
        $item_description = $request->idescription;
        $item_payment_type = $request->payment_type;

        $exp_invoice= ExpenseInvoice::create([
                'business_unit_id' => isset($this->cuser_business_unit_id) ? $this->cuser_business_unit_id : 0,
                'branch_id' => $request->branch_id,
                'project_id' => ($request->project_id) ? $request->project_id : 0,
                'invoice_no' => $invoice_no,
                'invoice_date' => $request->invoice_date,
                'total_amount' => $request->total_amount,
                'currency' => $request->currency,
                'exchange_rate' => $request->exchange_rate,
                'for_date' => $request->for_date,
                'return_total_amount' => 0,
                'description' => $request->description,
                'upload_user_id' => Auth::id(),
                'appoved_manager_id' => 0,
                'manager_status' => 'pending',
                'appoved_admin_id' => 0,
                'admin_status' => 'pending',
                'edit_by' => Auth::id(),
            ]);

        foreach($request->items as $itind => $item){
            $item_cate = Item::where('id',$item)->first();

            ExpenseInvoiceItem::create([
                'category_id' => $item_cate->category_id,
                'invoice_id' => $exp_invoice->id,
                'invoice_type' => 'expense',
                'item_id' => $item,
                'qty' => $item_quantity[$itind],
                'amount' => $item_amount[$itind],
                'unit_id' => $item_unit_ids[$itind],
                'item_description' => $item_description[$itind],
                'payment_type' => $item_payment_type[$itind],
            ]);
        }

        if($request->hasfile('docs')) {

            $i=1;
            foreach($request->file('docs') as $file){

                $upload_path = 'expense_docs/'.$exp_invoice->id;
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0775, true);  //create directory if not exist
                }

                $file_name = time(). '_'. $i . '.' . $file->getClientOriginalExtension();
                $org_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $file->move(public_path($upload_path), $file_name);

                InvoiceDocument::create([
                    'invoice_no' => 'EXINV-'.$exp_invoice->invoice_no,
                    'title' => $org_file_name,
                    'inv_file' => $upload_path.'/'.$file_name
                ]);

                $i++;
            }

        }
        return redirect('/expense-invoice')->with('success', 'New Expense Invoice Added successfully.');

        // return redirect('/expense-invoice')->with('error', 'Failed to add Expense Invoice!');
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
        $invoice_items = ExpenseInvoiceItem::where('invoice_id', $id)->where('invoice_type','expense')->get();
        $invoice_docs = InvoiceDocument::where('invoice_no', $invoice_no)->get();
        $invoice_notes = InvoiceNote::where('invoice_no', $invoice_no)->get();

        $data['user_role'] = Auth::user()->user_role;

        return view('cfms.expense-invoice.view', compact('invoice', 'invoice_items','invoice_docs','invoice_no', 'invoice_notes', 'data'));
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
        $submit_btn_control = true;

        $itemcategories = ItemCategory::where('business_unit_id', $this->cuser_business_unit_id)->get();
        $statuses = $this->statuses;

        $invoice = ExpenseInvoice::find($id);
        $businessUnits = BusinessUnit::where('id', $invoice->business_unit_id)->get();
        $branches = array();

        foreach ($businessUnits as $businessUnit) {
            $optgroupLabel = $businessUnit->name;
            $branchOptions = Branch::where('business_unit_id', $invoice->business_unit_id)->pluck('name', 'id')->toArray();
            $branches[$optgroupLabel] = $branchOptions;
        }

        $invoice_no = 'EXINV-'.$invoice->invoice_no;
        $invoice_items = ExpenseInvoiceItem::where('invoice_id', $id)->where('invoice_type','expense')->get();
        $invoice_docs = InvoiceDocument::where('invoice_no', $invoice_no)->get();
        $invoice_notes = InvoiceNote::where('invoice_no', $invoice_no)->get();
        $itemunits = ItemUnit::get();

        //for submit btn control
        if(Auth::user()->user_role == "Staff" && $invoice->admin_status != "pending"){
            $submit_btn_control = false;
        }
        $data['submit_btn_control'] = $submit_btn_control;
        $data['user_role'] = Auth::user()->user_role;

        $data['hr_statuses'] = $this->hr_statuses;
        $data['manager_statuses'] = $this->manager_statuses;
        $data['account_statuses'] = $this->account_statuses;

        return view('cfms.expense-invoice.edit', compact('invoice', 'invoice_items','invoice_docs','invoice_no','itemcategories','branches', 'statuses', 'invoice_notes', 'data', 'itemunits'));
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
            'invoice_date'  =>  'required',
            'total_amount'  =>  'required'
        ]);

        $exp_invoice = ExpenseInvoice::find($id);

        if($exp_invoice->total_amount < $request->total_amount && $request->status != "pending"){
            return redirect('/expense-invoice')->with('error', "Expected total amount should not greater than the final claimed total amount.");
        }

        $item_amount = $request->amount;
        $item_quantity = $request->quantity;
        $item_unit_ids = $request->unit_ids;
        $item_description = $request->idescription;
        $item_payment_type = $request->payment_type;

        $updated_total_amt = 0;

        if(!empty($request->invitem)){
            foreach($request->invitem as $itind => $item){

                $exp_invoice_item = ExpenseInvoiceItem::find($item);
                $exp_invoice_item->qty = $item_quantity[$itind];
                $exp_invoice_item->amount = $item_amount[$itind];
                $exp_invoice_item->unit_id = $item_unit_ids[$itind];
                $exp_invoice_item->item_description = $item_description[$itind];
                $exp_invoice_item->payment_type = $item_payment_type[$itind];
                $exp_invoice_item->save();

                $updated_total_amt += $item_quantity[$itind] * $item_amount[$itind];
            }
        }

        $items = $request->items_up;
        $item_amount_up = $request->amount_up;
        $item_quantity_up = $request->quantity_up;
        $item_unit_ids_up = $request->unit_ids_up;
        $item_description_up = $request->idescription_up;
        $item_payment_type_up = $request->payment_type_up;

        if(!empty($request->category_ids_up)){
            foreach($request->category_ids_up as $itind => $category_id){

                ExpenseInvoiceItem::create([
                    'category_id' => $category_id,
                    'invoice_id' => $id,
                    'invoice_type' => 'expense',
                    'item_id' => $items[$itind],
                    'qty' => $item_quantity_up[$itind],
                    'amount' => $item_amount_up[$itind],
                    'unit_id' => $item_unit_ids_up[$itind],
                    'item_description' => $item_description_up[$itind],
                    'payment_type' => $item_payment_type_up[$itind],
                ]);

                $updated_total_amt += $item_quantity_up[$itind] * $item_amount_up[$itind];
            }
        }

        //update the invoice data
        // $exp_invoice->branch_id = $request->branch_id;
        // $exp_invoice->project_id = ($request->project_id) ? $request->project_id : 0;
        $exp_invoice->invoice_date = $request->invoice_date;
        if($request->status == 'pending'){
            $exp_invoice->total_amount = $updated_total_amt;
        }elseif($request->status == 'claimed'){
            // not save
        }else{
            $exp_invoice->f_claimed_total = $updated_total_amt;
        }
        
        $exp_invoice->description = $request->description;
        $exp_invoice->for_date = $request->for_date;
        if(Auth::user()->role->name != "Staff"){
            $exp_invoice->manager_status = $request->status;
            $exp_invoice->admin_status = $request->status;
        }
        if(Auth::user()->role->name == "Admin"){
            $exp_invoice->appoved_admin_id = Auth::id();
        }elseif(Auth::user()->role->name == "Manager") {
            $exp_invoice->appoved_manager_id = Auth::id();
        }
        $exp_invoice->edit_by = Auth::id();
        $exp_invoice->save();


        if($request->hasfile('docs')) {

            $i=1;
            foreach($request->file('docs') as $file){

                $upload_path = 'expense_docs/'.$exp_invoice->id;
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0775, true);  //create directory if not exist
                }

                $file_name = time(). '_'. $i . '.' . $file->getClientOriginalExtension();
                $org_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $file->move(public_path($upload_path), $file_name);

                InvoiceDocument::create([
                    'invoice_no' => 'EXINV-'.$exp_invoice->invoice_no,
                    'title' => $org_file_name,
                    'inv_file' => $upload_path.'/'.$file_name
                ]);

                $i++;
            }

        }
        return redirect('/expense-invoice')->with('success', 'Expense Invoice updated successfully.');

        // return redirect('/expense-invoice')->with('error', 'Failed to update Expense Invoice!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exp_invoice = ExpenseInvoice::find($id);
        $exp_invoice->delete();
        return redirect('/expense-invoice')->with('success', 'Expense Invoice deleted successfully.');
    }

    public function add_inv_note(Request $request, $id){
        $request->validate([
            'invoice_note'  =>  'required',
        ]);

        $invoice = ExpenseInvoice::find($id);
        // $invoice->manager_status = $request->status;
        // $invoice->admin_status = $request->status;
        // $invoice->save();
        $invoice_no = 'EXINV-'.$invoice->invoice_no;

        InvoiceNote::create([
                'invoice_no' => $invoice_no,
                'description' => $request->invoice_note,
                'status' => $request->status,
                'added_by' => Auth::id()
            ]);

        return back()->with("success", "Successfully add the invoice note.");
    }

    public function get_item_history(Request $request){
        $item_id = $request->item_id;
        $items = ExpenseInvoiceItem::with('invoice','item')->where('item_id', $item_id)->where('invoice_type','expense')->orderBy('id', 'desc')->take(10)->get()->toArray();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function get_expense_invoice($id){
        $invoice = ExpenseInvoice::find($id);
        $invoice_no = 'EXINV-'.$invoice->invoice_no;
        $invoice_items = ExpenseInvoiceItem::where('invoice_id', $id)->where('invoice_type','expense')->get();

        return view('cfms.expense-invoice.invoice', compact('invoice', 'invoice_items','invoice_no'));
    }

    public function delete_edit_item($id){
        $exp_invoice_item = ExpenseInvoiceItem::find($id);

        $expense_invoice = $exp_invoice_item->invoice;

        // Calculate the amount to subtract based on quantity and unit price
        $amount_to_subtract = $exp_invoice_item->qty * $exp_invoice_item->amount;

        // Subtract the calculated amount from the total amount
        $expense_invoice->total_amount -= $amount_to_subtract;

        // Save the updated total amount
        $expense_invoice->save();

        $exp_invoice_item->delete();
        return back()->with("success", "Successfully delete the invoice item.");
    }

    public function delete_item_doc($id){
        $invoice_doc = InvoiceDocument::find($id);

        $old_inv_file = $invoice_doc->inv_file;

        if(file_exists(public_path($old_inv_file))){
            unlink(public_path($old_inv_file));
        }

        $invoice_doc->delete();
        return back()->with("success", "Successfully delete the invoice doc.");
    }
}
