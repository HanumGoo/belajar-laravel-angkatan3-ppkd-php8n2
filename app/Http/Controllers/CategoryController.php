<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $title = "Data Category";
        $categories = Category::orderBy('name', 'asc')->get();
        return view('category.index', compact('title', 'categories'));
    }
    public function create()
    {
        $title = "Create Role";
        return view('category.create', compact('title'));
    }
    public function store(Request $request)
    {

        Category::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);
        return redirect()->to('category');
    }
    public function edit(int $id)
    {
        $title = "Edit Category";
        $categories = Category::findOrFail($id);
        return view('category.edit', compact('title', 'categories'));
    }
    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);
        return redirect()->to('category');
    }
    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->to('category');
    }
}
