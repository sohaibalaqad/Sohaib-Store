<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $product = Product::latest()
            ->limit(10)
            ->get();
        return view('home', [
            'products' => $product,
        ]);
    }
}
