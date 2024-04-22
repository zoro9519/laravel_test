<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get();
        return view('category.category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.category-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100|unique:categories',
        ]);

        $category = Category::create($request->all());

        Alert::success('Success', 'Category has been saved !');
        return redirect('/category');
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
        $category = Category::findOrFail($id);
        return view('category.category-edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:100|unique:categories',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        Alert::info('Success', 'Good has been updated !');
        return redirect('/category');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deletedCategory = Category::findOrFail($id);

            $deletedCategory->delete();

            Alert::error('Success', 'Category has been deleted !');
            return redirect('/category');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Category already used !');
            return redirect('/category');
        }
    }
}
