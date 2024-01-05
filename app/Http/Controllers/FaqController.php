<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Resources\FaqResource;
use Illuminate\Support\Facades\Validator;

class FaqController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::paginate(8);
        $faqall = Faq::all();
        // dd($count);

        return $this->successResponse([
            'faqall' => FaqResource::collection($faqall),
            'faqs' => FaqResource::collection($faqs),
            'links' => FaqResource::collection($faqs)->response()->getData()->links,
            'meta' => FaqResource::collection($faqs)->response()->getData()->meta,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'fullname'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'phone'=>'required',
            'message'=>'required'

        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages(),422);
        }

       $faq =Faq::create([
            'fullname'=>$request->fullname,
            'email'=>$request->email,
            'phonenumber'=>$request->phone,
            'subject'=>$request->subject,
            'message'=>$request->message,


        ]);

        return $this->successResponse($faq,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {

       return $this->successResponse($faq,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {

        // dd($faq->email);
            $validator =Validator::make($request->all(),[
            'fullname'=>'nullable',
            'email'=>'nullable',
            'subject'=>'nullable',
            'phone'=>'nullable',
            'message'=>'nullable',
            'is_active'=>'nullable',
            'answer'=>'nullable'

        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->messages(),422);
        }

       $faq->update([
            'fullname'=>$request->has('fullname') ? $request->fullname : $faq->fullname,
            'email'=>$request->has("email") ? $request->email : $faq->email,
            'phonenumber'=>$request->has('phone') ? $request->phone : $faq->phone,
            'subject'=>$request->has('subject') ? $request->subject : $faq->subject,
            'message'=>$request->has('message') ? $request->message : $faq->message,
            'is_active'=>$request->has('is_active') ? $request->is_active : $faq->is_active,
            'answer'=>$request->has('answer') ? $request->answer : $faq->answer


        ]);

        return $this->successResponse($faq,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
