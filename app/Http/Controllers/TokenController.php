<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Token;
use \App\Models\User;
use \App\Models\Ip;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DateTimeZone;


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
        $user_ip=$request->ip();
        $ip =Ip::haveAccess($user_ip);
        if (gettype($ip)!="integer")
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
                    $this->help($ip,$user_ip);
                    return response(["response"=>"Identifiants non trouvé"],404);
                }
            } catch (\Throwable $th) {
                return response(["response"=>"donnees incomprehensible ou incomplete.","erreur"=>$th],422);
            }

        return response(["response"=>"Plus de 3 échecs en 5 min,Réessayer 30 minutes apres."],429);
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

    public function help($ip,$user_ip)
    {
        $date=new DateTime();
        $date->setTimezone(new DateTimeZone('Europe/Paris'));

        if($ip==null){
            try {
                Ip::create(["Ip_address"=>$user_ip,
                "nb_tentive"=>1,
                "first_request_in_5_min"=>$date,
                "last_request_in_5_min"=>$date
                ]);
            } catch (\Throwable $th) {
                return response(["response"=>$th],404);
            }
        }else{
            if($ip->nb_tentive==2){
                $date->modify("+30 minutes");
                $ip->next_possible_connexion=$date;
                $ip->save();
            }else{
                $ip->nb_tentive=$ip->nb_tentive + 1;
                $ip->save();
            }
        }
    }
}
