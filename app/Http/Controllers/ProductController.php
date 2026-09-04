<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $title = "Data Product";
        return view('product.index', compact('title', 'products'));
    }
    public function create()
    {
        $title = "Create Role";
        $categories = Category::get();
        return view('product.create', compact('title', 'categories'));
    }
    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'description' => $request->description
        ];
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }
        Product::create($data);
        return redirect()->to('product')->with('success', 'create product success');
    }
    public function edit(Request $request, int $id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::get();
        $title = "Edit Product";
        return view('product.edit', compact('title', 'product', 'categories'));
    }
    public function update(Request $request, Product $product)
    {
        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'description' => $request->description
        ];
        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }
        $product->update($data);
        return redirect()->to('product')->with('success', 'create product success');
    }
    public function destroy(Product $product)
    {
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }
        $product->delete();

        return redirect()->to('product')->with('success', 'Delete Berhasil');
    }
}
