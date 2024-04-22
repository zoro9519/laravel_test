<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createRate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'book_id' => 'required',
            'rate' => 'required|min:0|max:10',
        ]);

        $book_id = $request->get('book_id');
        $rate = Rate::create([
            'title' => $request->get('title'),
            'rate' => $request->get('rate'),
            'user_id' => auth()->check() ? auth()->user()->id : 0,
            'book_id' => $request->get('book_id')
        ]);

        $book = Book::findOrFail($request->get('book_id'));
        $book->rate = Rate::where('book_id', $book_id)->avg('rate');
        $book->save();
        Alert::success('Success', 'Rate has been saved !');
        return redirect('/');
    }
}
