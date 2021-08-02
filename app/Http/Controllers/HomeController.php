<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
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

    public function getUser(){
        $users = User::with('profile')->get();
        foreach ($users as $user){
            echo $user->profile->address. '<br>';
        }
    }
}
