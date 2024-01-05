<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(8);
        $categorieall = Category::all();
       return $this->successResponse(['categoryall'=>$categorieall,
         'categories' => CategoryResource::collection($categories),
        'links' => CategoryResource::collection($categories)->response()->getData()->links,
        'meta' => CategoryResource::collection($categories)->response()->getData()->meta,],200);
    }

    /**
     * Store a newly created resource in storage.
     */


    // public function create()
    // {

    //     $attribute = Attribute::all();

    //     return $this->successResponse($attribute,200);
    // }



    public function store(Request $request)
    {

        // dd($request);




        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required',
            'description'=>'required',
            'is_active'=>'required',
            'attribute_id'=>'required',
            'is_filter'=>'required',
            'is_variation'=>'required'



        ]);

        if($validator->fails()){
            $this->errorResponse($validator->messages(),422);

        }



            $fileImage = Carbon::now()->microsecond.'.'.$request->file('image')->getClientOriginalName();

            $request->file('image')->storeAs('image/categories',$fileImage, 'public');

            $category=Category::create([


            'name'=>$request->name,
            'slug'=>$request->slug,
            'image'=>$fileImage,
            'description'=>$request->description,
            'is_active'=>$request->is_active,


            ]);

            foreach ($request->attribute_id as $attributeId) {
            $attribute = Attribute::findOrFail($attributeId);
            $attribute->categories()->attach($category->id, [
                'is_filter' => in_array($attributeId, $request->is_filter) ? 1 : 0,
                'is_variation' => $request->is_variation == $attributeId ? 1 : 0
            ]);
            }






        return $this->successResponse($category,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
       return $this->successResponse(new CategoryResource($category),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required',
            'description'=>'required',


        ]);

        if($validator->fails()){
            $this->errorResponse($validator->messages(),422);

        }

        $category->update([
            'parent_id'=>$request->parent_id,
            'name'=>$request->name,
            'slug'=>$request->slug,
            'description'=>$request->description,
            'is_active'=>$request->is_active,
            'icon'=>$request->icon

        ]);

        return $this->successResponse($category,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category = $category->delete();
        return $this->successResponse($category, 200);
    }



    public function getCategoryAttribute(Category $category){

       $attributes=$category->attributes()->wherePivot('is_variation',0)->get();
       $variation= $category->attributes()->wherePivot('is_variation',1)->first();

       return $this->successResponse([
        'attributes'=>$attributes,
        'variation'=>$variation,
       ],200);




    }


}
