<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{

    public function __construct()
    {
        $this->middleware([
            'auth',
            // 'verified',  // verify email
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of category model object
        // SELECT * FROM categories;
        // $entries = Category::all();

        // return collection of std Object
        /*
        $entries = DB::table('categories')
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'DESC')
            ->orderBy('name', 'ASC')
            ->get();
        */

        /*
        SELECT categories.*, parents.name as parent_name FROM
        categories LEFT JOIN categories as parents
        ON parents.id = categories.parent_id
        WHERE ststus = 'active'
        ORDER BY created_at DESC, name ASC
        */

        /*$entries = Category::leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])
            // ->where('categories.status', '=', 'active')
            ->orderBy('categories.created_at', 'DESC')
            // ->orderBy('categories.name', 'ASC')
            ->get();
        */

        $entries = Category::withCount('products as count')->get();
//        dd($categories);

        $success = session()->get('success');

        return view('admin.categories.index')->with([
            'categories' => $entries,
            'title' => 'Categories List',
            'success' => $success,
        ]);
        // OR
        // return view('admin.categories.index', compact('entries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        return view('admin.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {

        /**
         * Validation
         */
        // $rules = [
        //     'name'=> 'required|string|max:255|min:3|unique:categories',
        //     'parent_id' => 'nullable|int|exists:categories,id',
        //     'description' => 'nullable|min:5',
        //     'status' => 'required|in:active,draft',
        //     'image' => 'image|max:512000|dimensions:min_width=300,min_height=300',
        // ];
        // Method #1 validate in request object
        // $clean = $request->validate($rules, [
        //     'required' => 'حقل :attribute مطلوب',
        //     'name.required' => 'Category name is required!',
        // ]);
        /*// Method #2 validate in Controller
        $clean = $this->validate($request, $rules, []);
        // Method #3 (Adjust settings manually)
        $data = $request->all();
        $validator = Validator::make($data, $rules, []);
        // $clean = $validator->validate();
        if ($validator->fails()){ // return true if there is an error
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
        }  */

        // return array of all form fields
        // $request->all();

        // return single field value , (5 method)
        // $request->name;
        // $request->input('name');
        // $request->get('name');
        // $request->post('name');
        // $request->query('name'); // ?name=value

        // Method #1
        /*$category = new Category();
        $category->name = $request->post('name');
        $category->slug = Str::slug($request->post('name'));
        $category->parent_id = $request->post('parent_id');
        $category->description = $request->post('description');
        $category->status = $request->post('status', 'active');
        $category->save();*/

        // Request Merge
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);
        // Method #2 >> Mass assignment
        $category = Category::create($request->all());

        // Method #3 >> Mass assignment
        /*$category = new Category([
            'name' => $request->post('name'),
            'slug' => Str::slug($request->post('name')),
            'parent_id' => $request->post('parent_id'),
            'description' => $request->post('description'),
            'status' => $request->post('status', 'active'),
        ]);
        $category->save();*/

        return redirect()->route('categories.index')
            ->with('success', 'Category Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category->products()->count();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $category = Category::where('id', '=', 'id')->first();
        $category = Category::find($id);
        if (!$category) {
            abort(404);
        }
        $parents = Category::withTrashed()
            ->where('id', '<>', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);

        // Method #1
        /*$category->name = $request->post('name');
        $category->parent_id = $request->post('parent_id');
        $category->description = $request->post('description');
        $category->status = $request->post('status');
        $category->save();*/

        // Method #2 :: Mass assignment
        $category->update($request->all());

        // Method #3 :: Mass assignment
        // $category->fill($request->all())->save();

        // PRG
        return redirect()->route('categories.index')
            ->with('success', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Method #1
        /*$category = Category::find($id);
        $category->delete();*/

        // Method #2
        Category::destroy($id);

        // Method #3
        // Category::where('id', '=', $id)->delete();

        /**
         * flash message
         * store message (write into session)
         */
        // Session::put();
        // OR
        // session([
        //     'success'=> 'Category Deleted',
        // ]);
        // OR
        // session()->put('success', 'Category Deleted');
        /* read message (read from session)  */
        // Session::get();
        // OR
        // session('success');
        // OR
        // session()->get('success');
        /* delete message (delete from session) */
        // Session::forget();
        // OR
        // session()->forget('success');
        /**
         * OR this method
         */
        // session()->flash('success', 'Category Deleted');

        // PRG
        return redirect()->route('categories.index')
            ->with('success', 'Category Deleted');
    }

}
