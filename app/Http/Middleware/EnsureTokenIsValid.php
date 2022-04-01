<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Models\Token;
class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_token = $request->bearerToken();

		if($user_token){
			$token=Token::isValid($user_token);
			if($token)
                return $next($request);
        	else
				return response(["response"=>"Paramètres de connection invalide: admin token manquant et / ou incorrect et / ou token invalide"],422);
		}else
			return response(["response"=>"Il est nécessaire d'être authentifié"],401);
    }
}
