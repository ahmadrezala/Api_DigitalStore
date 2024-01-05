<?php

namespace App\Http\Controllers\home;

use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;

class ProductController extends Controller
{
    use ApiResponser;


    public function index()
    {
        $product = Product::Search()->paginate(8);
        $productall = Product::all();
        $productfucher = Product::take(9)->get();

       return $this->successResponse([
            'products'=>ProductsResource::collection($product->load('brand')->load('category')),
            'productsall'=>ProductsResource::collection($productall),
            'links'=>ProductsResource::collection($product)->response()->getData()->links,
            'meta'=>ProductsResource::collection($product)->response()->getData()->meta,
            'productfucher'=>ProductsResource::collection($productfucher),




        ],200);
    }


    public function show(Product $product)
    {


       $attribute =$product->attributes()->with('attribute')->get();



        return $this->successResponse(new ProductsResource([
           'product'=> $product->load('images')->load('variations'),
           'attribute' => $attribute



        ]),200);



    }


}
