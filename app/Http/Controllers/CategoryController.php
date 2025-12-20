<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. Show the list of categories
    public function index()
    {
        $categories = Category::all(); // Fetch all from DB
        return view('categories.index', compact('categories'));
    }

    // 2. Show the form to add a new category
    public function create()
    {
        return view('categories.create');
    }

    // 3. Save the new category to the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }
    // 4. Show the Edit Form
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // 5. Update the changes in the Database
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    // 6. Delete the Category
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Category deleted successfully.');
    }
}