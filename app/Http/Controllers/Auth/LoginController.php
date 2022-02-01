<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $user = User::where(['email'=>$request->email])->first();
        
        //$token = $user->createToken('api');cannot create token before 
        //authentication  fails/success.
        
        if(!$user || !Hash::check($request->password,$user->password)){
            return response('Credentials Not Matched',
                            Response::HTTP_UNAUTHORIZED);
        }
       
        $token = $user->createToken('api');
        
        return response([
             'token' => $token->plainTextToken
        ]);
    }
}
