<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Token;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;



class TokenController extends Controller
{
    
    public function Valide(Request $requete ,$accessToken)
    {
        try {
            $token=Token::isValid($accessToken);
            if ($token)
                return response(["accessToken"=>$token->accessToken,"accessTokenExpiresAt"=>$token->accessTokenExpiresAt],200);
            else
                return response(["response"=>"Token non trouvé / invalide"],404);
            
        } catch (\Throwable $th) {
            return response([
                "response"=>"probleme de seveur",
                "erreur"=>$th
            ],500);
        }

       
    }


    public function Create(Request $request)
    {   
        try {
            $validatedData=$request->validate([
    			'login'=>['required','email:rfc,dns,filter,spoof,strict'],
    			'password'=>['required','string'],
		    ]);

            if(Auth::attempt([
                "login"=>$validatedData["login"],
                "password"=>$validatedData["password"]])
            ){
                $user=Auth::User();
                $token=$user->createToken();
                return response($token,200);
            }else{
                return response(["response"=>"Identifiants non trouvé"],404);
            }
        } catch (\Throwable $th) {
            return response(["response"=>"donnees incomprehensible ou incomplete.","erreur"=>$th],422);
        }
    }

    public function Refresh(Request $requete ,$refreshToken)
    {
        $token=Token::isValid($refreshToken,"refreshToken");
        if($token){
            $token=$token->user->createToken();
            return response($token,200);
        }
        
        return response(["response"=>"refreshToken non trouvé / invalide"],404);
    }
}
