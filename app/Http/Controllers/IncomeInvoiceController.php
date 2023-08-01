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
        $income_invoices = IncomeInvoice::paginate(25); 
        return view('cfms.income-invoice.index', compact('income_invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $itemcategories = ItemCategory::where('business_unit_id', 0)->get();
        $items = Item::where('invoice_type_id', 0)->get();
        $statuses = $this->statuses;

        $businessUnits = BusinessUnit::all();
        $branches = array();

        foreach ($businessUnits as $businessUnit) {
            $optgroupLabel = $businessUnit->name;
            $branchOptions = Branch::where('business_unit_id', $businessUnit->id)->pluck('name', 'id')->toArray();
            $branches[$optgroupLabel] = $branchOptions;
        }
        // var_dump($branches);exit;

        return view('cfms.income-invoice.create', compact('itemcategories','items','statuses','branches'));
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
                'business_unit_id' => 0,
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

        foreach($request->items as $itind => $item){
            $item_cate = Item::where('id',$item)->first();

            IncomeInvoiceItem::create([
                'category_id' => $item_cate->category_id,
                'invoice_id' => $exp_invoice->id,
                'item_id' => $item,
                'qty' => $item_quantity[$itind],
                'amount' => $item_amount[$itind],
            ]);
        }

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
        // $book = Book::find($id);
        // $child_books = Book::where('parent_id', $id)->where('status', 'active')->get();

        // $book_chapters = BookChapter::where('book_id', $id)->paginate(40);
        // return view('lazycomic.book.view', compact('book', 'book_chapters','child_books'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $book = Book::find($id);
        // $hasmultibook = Book::where('book_level', 'hasBooks')->get();
        // return view('lazycomic.book.edit', compact('book','hasmultibook'));
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
        //     'title'                 =>  'required',
        //     'total_chapter_des'     =>  'required',
        //     'coin'                  =>  'required|integer',
        //     'cover_image'           =>  'image|mimes:jpg,png,jpeg|max:3072'
        // ]); 

        // $image_path = 'images/bookcover/';

        // $book = Book::find($id);
        // $book->title = $request->title;
        // $book->total_chapter_des = $request->total_chapter_des;
        // $book->description = $request->description;
        // if($request->hasfile('cover_image')) {
        //     $old_book_cover = $book->cover_image;

        //     if(file_exists(public_path($old_book_cover))){
        //         unlink(public_path($old_book_cover));
        //     }
        //     $file_name = time() . '.' . request()->cover_image->getClientOriginalExtension();

        //     request()->cover_image->move(public_path('images/bookcover'), $file_name);
        //     $book->cover_image = $image_path.$file_name;
        // }
        
        // $book->book_type = $request->book_type;
        // $book->rating = $request->rating;
        // $book->book_level = $request->book_level;
        // $book->parent_id = ($request->book_level == "default" || $request->book_level == "hasBooks") ? 0 : $request->parent_id;
        // $book->book_status = $request->book_status;
        // $book->coin = $request->coin;
        // $book->is_slider = $request->is_slider;
        // $book->status = $request->status;
        // $book->save();

        // return redirect()->route('book.index')->with('success', 'New Book Edited successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $book = Book::find($id);
        // $book->delete();
        return redirect('/income-invoice')->with('success', 'Expense Invoice deleted successfully.');
    }
}
