<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    /**
     * @var CartRepository
     */
    protected $cart;

    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;

    }

    public function index()
    {
        $this->cart->add(Product::find(1), 2);
        return $this->cart->all();

//        $cart = app(CartRepository::class);
//        $cart = app()->make(CartRepository::class);
//        $cart = App::make(CartRepository::class);
    }


}
