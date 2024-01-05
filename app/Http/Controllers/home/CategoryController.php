<?php

namespace App\Http\Controllers\home;

use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryproductResource;

class CategoryController extends Controller
{
    use ApiResponser;


    public function show(Category $category)
    {


        



        return $this->successResponse(new CategoryproductResource($category->load('products')),200);



    }
}
