<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use App\Http\Resources\ProductsResource;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::paginate(6);
        $productall = Product::all();

       return $this->successResponse([
            'products'=>ProductsResource::collection($product->load('brand')->load('category')),
            'productsall'=>ProductsResource::collection($productall),
            'links'=>ProductsResource::collection($product)->response()->getData()->links,
            'meta'=>ProductsResource::collection($product)->response()->getData()->meta,




        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->attribute_id);

        // dd($request->file('images'));

        $validator = Validator::make($request->all() , [
            'name'=>'required',
            'status'=>'required',
            'description'=>'required',
            'product_price'=>'required',
            'brand_id'=>'required',
            'category_id'=>'required',
            'attribute_id'=>'required',
            'category_id'=>'required',
            'value_variation'=>'required',
            'price_variation'=>'required',
            'quantity_variation'=>'required',
            'sku_variation'=>'required'

        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages(),422);
        }

        $primaryImageName = Carbon::now()->microsecond.'.'.$request->file('primery_image')->getClientOriginalName();
        $request->file('primery_image')->storeAs('image/products',$primaryImageName, 'public');


        if($request->hasFile('images')){
            $fileNameImages = [];
            foreach($request->file('images') as $image){
                $fileNameImage = Carbon::now()->microsecond.'.'.$image->getClientOriginalName();
                $image->storeAs('image/products',$fileNameImage,'public');
                array_push($fileNameImages, $fileNameImage);

            }

        }





        $product=Product::create([
            'name'=>$request->name,
            'brand_id'=>$request->brand_id,
            'category_id'=>$request->category_id,

            'primary_image'=>$primaryImageName,
            'description'=>$request->description,
            'price'=>$request->product_price,
            'status'=>$request->status,


        ]);

        if($request->hasFile('images')){
            foreach($fileNameImages as $fileNameImage){

                ProductImage::create([
                    'product_id'=>$product->id,
                    'image'=>$fileNameImage
                ]);
            }


        }


        foreach($request->attribute_id as $key => $value){

            ProductAttribute::create([

                'attribute_id'=>$key,
                'product_id'=>$product->id,
                'value'=>$value

            ]);

        }





        $counter = count($request->value_variation);

        $category = Category::find($request->category_id);

        for($i=0; $i < $counter; $i++){

            ProductVariation::create([
                'attribute_id'=> $category->attributes()->wherePivot('is_variation',1)->first()->id,
                'product_id'=>$product->id,
                'value'=>$request->value_variation[$i],
                'price'=>$request->price_variation[$i],
                'quantity'=>$request->quantity_variation[$i],
                'sku'=>$request->sku_variation[$i]

            ]);

        }





        return $this->successResponse(new ProductsResource($product),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->successResponse(new ProductsResource($product),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product = $product->delete();
        return $this->successResponse($product, 200);
    }
}
