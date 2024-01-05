<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Resources\AttributeResource;
use Illuminate\Support\Facades\Validator;

class AttributeController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes= Attribute::paginate(8);
        $attributeall = Attribute::all();
        return $this->successResponse([
            'attributeall'=>AttributeResource::collection($attributeall),
            'attribute'=>AttributeResource::collection($attributes),
            'links'=>AttributeResource::collection($attributes)->response()->getData()->links,
            'meta'=>AttributeResource::collection($attributes)->response()->getData()->meta,

        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required',



        ]);

        if($validator->fails()){
            $this->errorResponse($validator->messages(),422);

        }

        $attribute=Attribute::create([

            'name'=>$request->name,


        ]);

        return $this->successResponse($attribute,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        return $this->successResponse(new AttributeResource($attribute),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required',



        ]);

        if($validator->fails()){
            $this->errorResponse($validator->messages(),422);

        }

        $attribute->update([

            'name'=>$request->name,


        ]);

        return $this->successResponse($attribute,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {

    // dd($attribute);
       $attribute->delete();
       return $this->successResponse($attribute,200);
    }
}
