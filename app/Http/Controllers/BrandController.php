<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;

class BrandController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::paginate(8);
        $brandall = Brand::all();
        // dd($count);

        return $this->successResponse([
            'brandall' => BrandResource::collection($brandall),
            'brands' => BrandResource::collection($brands),
            'links' => BrandResource::collection($brands)->response()->getData()->links,
            'meta' => BrandResource::collection($brands)->response()->getData()->meta,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all() , [
            'name'=>'required',
            'slug'=>'required',

        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages(),422);
        }

        $brand=Brand::create([
            'name'=>$request->name,
            'slug'=>$request->slug,
            'is_active'=>$request->is_active,

        ]);
        return $this->successResponse(new BrandResource($brand),200);


    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {

        return $this->successResponse(new BrandResource($brand),200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all() , [
            'name'=>'required',
            'slug'=>'required',

        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages(),422);
        }

        $brand->update([
            'name'=>$request->name,
            'slug'=>$request->slug,
            'is_active'=>$request->is_active,

        ]);
        return $this->successResponse(new BrandResource($brand),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {

        $barnd = $brand->delete();
        return $this->successResponse($brand, 200);
    }
}
