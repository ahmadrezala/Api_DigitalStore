<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Resources\BannerResource;
use Illuminate\Support\Facades\Validator;


class BannerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $banners= Banner::paginate(8);

       return $this->successResponse([
           'banners'=> BannerResource::collection($banners),
           'links'=>BannerResource::collection($banners)->response()->getdata()->links,
           'meta'=>BannerResource::collection($banners)->response()->getdata()->meta,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'primery_image'=>'required',
            'title'=>'required',
            'text'=>'required'

        ]);


        if($validator->fails()){
            $this->errorResponse($validator->messages(),400);
        }

        $primerImage = Carbon::now()->microsecond.'.'.$request->file('primery_image')->getClientOriginalName();
        $request->file('primery_image')->storeAs('image/banners',$primerImage,'public');

        $banner = Banner::create([

            'primery image'=>$primerImage,
            'title'=>$request->title,
            'text'=>$request->text,


        ]);

       return $this->successResponse($banner,200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
       return $this->successResponse($banner,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {

        $validator = Validator::make($request->all() , [

            'primery_image'=>'required',
            'title'=>'required',
            'text'=>'required'


        ]);

        if($validator->fails()){
           return $this->errorResponse($validator->messages(),400);
        }

        $primeryImage = Carbon::now()->microsecond.'.'.$request->file('primery_image')->getClientOriginalName();

        $request->file('primery_image')->storeAs('image/banners',$primeryImage,'public');




        $banner->update([
            'primery image'=>$primeryImage,
            'title'=>$request->title,
            'text'=>$request->text,
        ]);


        return $this->successResponse($banner,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
       return $this->successResponse($banner,200);
    }
}
