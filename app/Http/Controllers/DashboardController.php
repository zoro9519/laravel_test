<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::orderBy('id', 'asc')->get();

        return view('dashboard.dashboard', compact('books'));
    }
}
