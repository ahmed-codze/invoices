<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();

        return view('products.products', [
            'sections'  => $sections,
            'products'  => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name'  => 'required|unique:products,product_name',
            'section_id'    => 'required',
            'description'   => 'required'
        ]);
        Product::create([
            'product_name'  => $request['product_name'],
            'section_id'    => $request['section_id'],
            'description'    => $request['description']
        ]);

        session()->flash('add-success', 'تم اضافة المنتج بنجاح');

        return redirect('/products');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $request->validate([
            'product_name'  => 'required|unique:products,product_name,' . $request->id,
            'section_id'    => 'required',
            'description'   => 'required'
        ]);

        $product = Product::findOrFail($request->id);

        $product->update([
            'product_name'  => $request->product_name,
            'section_id'    => $request->section_id,
            'description'   => $request->description
        ]);

        session()->flash('updating-success', 'تم تحيث المنتج بنجاح');

        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Product::findOrFail($request->id)->delete();
        session()->flash('deleting-success', 'تم حذف المنتج بنجاح');
        return redirect('/products');
    }
}
