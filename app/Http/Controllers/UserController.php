<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use \App\Models\Token;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserController extends Controller
{


	public function index(Request $request ,$id)
	{
	   
		$user_token=$request->bearerToken();
		if ($user_token) {
			$token=Token::isValid($user_token);
			if ($token) {
				if($id=="me")
					return response($token->user,200);
				else{
					$user=User::find($id);
					if($user)

						if($token->user->role == "ROLE_ADMIN" || $token->user->uid == $id)
							return response($user,200);
						else
							return response(["response"=>"Il est nécessaire d'être admin ou d'être le propriétaire du compte"],403);

					else
						return response(["response"=>"Aucun utilisateur trouvé avec l'UID donné"],404);
				}

			}else
			return response(["response"=>"Paramètres de connection invalide: admin token manquant et / ou incorrect et / ou token invalide"],422);
		
		}else
			return response(["response"=>"Il est nécessaire d'être authentifié"],401);
		
	}

	public function create(Request $request)
	{
		$user_token = $request->bearerToken();
		if($user_token){
			$token=Token::isValid($user_token);
			if($token)
				if ($token->user->role == "ROLE_ADMIN") {
					try {
						$validatedData=$request->validate([
							'nom'   => ['nullable','string','max:128'],
							'login'=>['required','email:rfc,dns,filter,spoof,strict'],
							'password'=>['required','string','max:100'],
							'role'=>['nullable','string',Rule::in(['ROLE_ADMIN','ROLE_USER'])],
							'status'=>['nullable','string',Rule::in(['OPEN','CLOSED'])]
						]);
						try {
							$id=Str::random(random_int(8, 20));
							$user=User::create([
								"uid"=>$id,
								"nom"=>$validatedData["nom"] ?? "",
								"login"=>$validatedData["login"],
								"password"=>Hash::make($validatedData["password"]),
								"role"=>$validatedData["role"]?? "ROLE_USER",
								"status"=>$validatedData["status"]?? "OPEN"
							]);
							$user["uid"]=$id;
							return response($user,201);
						} catch (\Throwable $th) {
							return response(["response"=>"Cet utilisateur existe deja ."],422);
						}
					
					} catch (\Throwable $th) {
						return response(["response"=>"donnees incomprehensible ou incomplete."],422);
					}

				}else 
					return response(["response"=>"Il est nécessaire de disposer d'un compte admin pour créer un compte."],403);
			else
				return response(["response"=>"Paramètres de connection invalide: admin token manquant et / ou incorrect et / ou token invalide"],422);
		}else
			return response(["response"=>"Il est nécessaire d'être authentifié"],401);
	}


	public function Modifier(Request $request ,$id)
	{
		$user_token = $request->bearerToken();
		if($user_token){
			$token=Token::isValid($user_token);
			if($token)
				try {
					$validatedData=$request->validate([
						'nom'   => ['nullable','string','max:128'],
						'login'=>['nullable','email:rfc,dns,filter,spoof,strict'],
						'password'=>['nullable','string','max:100'],
						'role'=>['nullable','string',Rule::in(['ROLE_ADMIN','ROLE_USER'])],
						'status'=>['nullable','string',Rule::in(['OPEN','CLOSED'])]
					]);
					try {
						$password=$validatedData["password"]?? null;
						$role=$validatedData["role"]??null;
						if($id=="me" ){
							if($role==null || $token->user->role == "ROLE_ADMIN"){
								$token->user->login=$validatedData["login"]??$token->user->login;
								$token->user->role=$validatedData["role"] ??$token->user->role;
								$token->user->name=$validatedData["nom"]??$token->user->name;
								$token->user->login=$validatedData["login"]??$token->user->login;
								$token->user->status=$validatedData["status"]??$token->user->status;
								if($password)
									$token->user->password=Hash::make($password);
								$token->user->save();
							}else
								return response(["response"=>"Il est nécessaire de disposer d'un compte admin pour modifier un role."],403);
							return response($token->user,201);
						}else{
							$user=User::find($id);
							if($user){
								if ($token->user->role == "ROLE_ADMIN") {
									$user->login=$validatedData["login"]??$user->login;
									$user->role=$validatedData["role"] ??$user->role;
									$user->name=$validatedData["nom"]??$user->name;
									$user->login=$validatedData["login"]??$user->login;
									$user->status=$validatedData["status"]??$user->status;
									if($password)
										$user->password=Hash::make($password);

									$user->save();
								}elseif($role==null && $user->id==$user->id){
									$user->login=$validatedData["login"]??$user->login;
									$user->name=$validatedData["nom"]??$user->name;
									$user->login=$validatedData["login"]??$user->login;
									$user->status=$validatedData["status"]??$user->status;
									if($password)
										$user->password=Hash::make($password);
									$user->save();
								}else
									return response(["response"=>"Il est nécessaire de disposer d'un compte admin pour modifier un role."],403);
								return response($user,201);
							}
							return response(["response"=>"Aucun utilisateur trouvé avec l'UID donné"],404);
						}

					} catch (\Throwable $th) {
						return response(["response"=>"Intener error","error"=>$th],500);
					}
				
				} catch (\Throwable $th) {
					return response(["response"=>"donnees incomprehensible ou incomplete."],422);
				}
			else
				return response(["response"=>"Paramètres de connection invalide: admin token manquant et / ou incorrect et / ou token invalide"],422);
		}else
			return response(["response"=>"Il est nécessaire d'être authentifié"],401);
	}
}
