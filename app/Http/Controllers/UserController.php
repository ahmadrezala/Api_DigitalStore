<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends ApiController
{

   public function me()
   {
        $user = User::find(Auth::id());

        return $this->successResponse(['user' => $user] , 200);


   }
}
