<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::join('categories', 'categories.id', '=', 'products.category_id')
            ->select([
                'products.*',
                'categories.name as category_name'
            ])
            ->paginate(10, ['*'], 'p');
        // OR 
        // ->simplepaginate();
        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', [
            'categories' => $categories,
            'product' => new Product(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Product::validateRules());
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);
        $product = Product::create($request->all());
        return redirect()->route('products.index')
            ->with('success', "Product $product->name Created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
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
        $product = Product::findOrFail($id);
        $request->validate(Product::validateRules());
        if ($request->hasFile('image')) {
            Storage::disk('uploads')->delete($product->image_path);
            $file = $request->file('image'); // uploudedFile object
            // store() Vs. storeAs : store=> random file name
            $image_path = $file->store('/',[
                'disk' => 'uploads'
            ]);
            $request->merge([
                'image_path' => $image_path,
            ]);

            // file information 
            /* $file->getClientOriginalName(); // return file name
            $file->getClientOriginalExtension(); // return file extension
            $file->getClientMimeType(); // Ex: image/jpeg
            $file->getType(); // Ex: image/jpeg
            $file->getSize(); // byte */

            /**
             * File System - Disks
             * 1. Local : storege/app
             * 2. Public : storege/app/public
             * 3. s3 : Amazon Drive
             * 4. custam : defined by us!
             */
        }
        $product->update($request->all());
        return redirect()->route('products.index')
            ->with('success', "Product $product->name updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        Storage::disk('uploads')->delete($product->image_path);
        return redirect()->route('products.index')
            ->with('success', "Product $product->name deleted.");
    }
}
