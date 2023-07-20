<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceNote;
use App\Models\InvoiceType;

class InvoiceController extends Controller
{
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
        $invoices = Invoice::paginate(25); 
        return view('cfms.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cfms.invoice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title'                 =>  'required',
        //     'total_chapter_des'     =>  'required',
        //     'coin'                  =>  'required|integer',
        //     'cover_image'           =>  'required|image|mimes:jpg,png,jpeg|max:3072'
        // ]); 

        // //dimension check
        // //required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000

        // $file_name = time() . '.' . request()->cover_image->getClientOriginalExtension();

        // request()->cover_image->move(public_path('images/bookcover'), $file_name);
        // $image_path = 'images/bookcover/';

        // $book = new Book;
        // $book->title = $request->title;
        // $book->total_chapter_des = $request->total_chapter_des;
        // $book->description = $request->description;
        // $book->cover_image = $image_path.$file_name;
        // $book->book_type = $request->book_type;
        // $book->rating = $request->rating;
        // $book->book_level = $request->book_level;
        // $book->parent_id = ($request->parent_id) ? $request->parent_id : 0;
        // $book->book_status = $request->book_status;
        // $book->coin = $request->coin;
        // $book->is_slider = $request->is_slider;
        // $book->status = $request->status;
        // $book->save();

        // return redirect()->route('book.index')->with('success', 'New Book Added successfully.');
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
        // return redirect()->route('book.index')->with('success', 'Book has been deleted successfully!');
    }
}
