<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Fluent;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\PdfToImage\Pdf;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->get('query', '');
        $books = Book::where('title', 'like', "%$query%")->orderBy('id', 'asc')->get();
        $categories = Category::all();
        return view('book.book', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('book.book-add', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'category_id' => 'required',
            'file' => 'required|mimes:pdf',
            'note' => 'required|max:255',
        ]);

        $book = Book::create($request->input());

        if ($request->has('file')) {
            $pdfUploaded = $request->file('file');
            $pdfName = $request->get('title') . '_' . time() . '.' . $pdfUploaded->getClientOriginalExtension();
            $pdfPath = public_path('/uploads/pdf');
            $pdfUploaded->move($pdfPath, $pdfName);
            $book->download_url = '/uploads/pdf/' . $pdfName;
            $book->save();
        }

        Alert::success('Success', 'Book has been saved !');
        return redirect('/book');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('book.book-edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'category_id' => 'required',
            'note' => 'required|max:1000',
        ]);

        $book = Book::findOrFail($id);
        $book->update($validated);

        Alert::info('Success', 'Good has been updated !');
        return redirect('/book');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deletedBook = Book::findOrFail($id);

            $deletedBook->delete();

            Alert::error('Success', 'Book has been deleted !');
            return redirect('/book');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Book already used !');
            return redirect('/book');
        }
    }

    /**
     * download pdf file
     */
    public function download($id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            Alert::warning('Error', "Can't download file!");
            return null;
        }
        return response()->download(public_path($book->download_url));
    }

    /**
     * set rate page
     */
    public function setRate($id)
    {
        $loginStatus = Auth::check();
        if (!$loginStatus) {
            Alert::warning('Warning', "Please log in now!");
            return redirect('/');
        }
        $book = Book::findOrFail($id);
        return view('rate.rate-edit', compact('book'));
    }
}
