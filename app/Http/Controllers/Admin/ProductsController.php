<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Scopes\ActiveStatusScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        $this->authorize('view-any', Product::class);
        $products = Product::with('category.parent')
//        join('categories', 'categories.id', '=', 'products.category_id')
            ->select([
                'products.*',
//                'categories.name as category_name'
            ])
            ->withoutGlobalScope(ActiveStatusScope::class)
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
        /*if(Gate::denies('products.create')){    // OR if(!Gate::allows('products.create'))
            abort(403);
        }*/
        $this->authorize('create', Product::class);
        $categories = Category::pluck('name', 'id');
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
        /*if(!Gate::allows('products.create')){
            abort(403);
        }*/
        $this->authorize('create', Product::class);
        $request->validate(Product::validateRules());
        // $request->merge([
        //     'slug' => Str::slug($request->post('name')),
        // ]);
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
        $product = Product::withoutGlobalScope(ActiveStatusScope::class)->findOrFail($id);
        return $product->ratings;
        $this->authorize('view', $product);
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
        $product = Product::withoutGlobalScope(ActiveStatusScope::class)->findOrFail($id);
        $this->authorize('update', $product);
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::withTrashed()->pluck('name', 'id'),
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
        $product = Product::withoutGlobalScope(ActiveStatusScope::class)->findOrFail($id);
        $this->authorize('update', $product);
        $request->validate(Product::validateRules());
        if ($request->hasFile('image')) {
            Storage::disk('uploads')->delete($product->image_path);
            $file = $request->file('image'); // uploudedFile object
            // store() Vs. storeAs : store=> random file name
            $image_path = $file->store('/', [
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
        // if(!Gate::allows('products.delete')){
        //     abort(403);
        // }
        // OR
        //Gate::authorize('products.delete');

        $product = Product::withoutGlobalScope(ActiveStatusScope::class)->findOrFail($id);
        $this->authorize('delete', $product);
        $product->delete();
        // Storage::disk('uploads')->delete($product->image_path);
        return redirect()->route('products.index')
            ->with('success', "Product $product->name deleted.");
    }

    public function trash()
    {
        $products = Product::withoutGlobalScope(ActiveStatusScope::class)->onlyTrashed()->paginate();
        return view('admin.products.trash', [
            'products' => $products,
        ]);
    }

    public function restore(Request $request, ?Product $product = null)
    {
        if ($product) {
            // $product = Product::withoutGlobalScope(ActiveStatusScope::class)->onlyTrashed()->findOrFail($id);
            $product->restore();
            return redirect()->route('products.index')
                ->with('success', "Product $product->name restred.");
        }
        Product::withoutGlobalScope(ActiveStatusScope::class)->onlyTrashed()->restore();
        return redirect()->route('products.index')
            ->with('success', "All trashed products restred.");
    }

    public function forceDelete($id = null)
    {
        if ($id) {
            $product = Product::withoutGlobalScope(ActiveStatusScope::class)->onlyTrashed()->findOrFail($id);
            $product->forceDelete();
            return redirect()->route('products.index')
                ->with('success', "Product $product->name deleted forever.");
        }
        Product::withoutGlobalScope(ActiveStatusScope::class)->onlyTrashed()->forceDelete();
        return redirect()->route('products.index')
            ->with('success', "All trashed products deleted forever.");
    }
}
