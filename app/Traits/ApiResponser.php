<?php

namespace App\Traits;

trait ApiResponser{



    protected function successResponse($data , $code=200, $massage=null){

        return response()->json([
            'status'=>'success',
            'massage'=>$massage,
            'data'=>$data,

        ],$code);

    }


    protected function errorResponse($massage , $code=200){



        return response()->json([
            'status'=>'error',
            'massage'=>$massage,
            'data'=>null
        ],$code);
    }






}


?>
