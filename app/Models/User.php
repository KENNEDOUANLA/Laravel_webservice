<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'uid';
    protected $keyType = 'string';
    protected $fillable = [
        'uid',
        'name',
        'login',
        'password',
        'status',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'status'
    ];

    public function token()
    {
       return $this->hasOne(Token::class,"uid");
    }

    public function createToken()
    {
        
        try {
            if($this->token){
                $this->token->update([
                    'accessToken'=>hash('sha256', $plainTextToken = \Str::random(20)),
                    'refreshToken'=>hash('sha256', $plainTextToken = \Str::random(20)),
                    'accessTokenExpiresAt'=>now()->addHours(1),
                    'refreshTokenExpiresAt'=>now()->addHours(2),
                ]);
                return $this->token ;
            }
            else{
                $token =Token::create([ 
                    'uid'=>$this->uid,
                    'accessToken'=>hash('sha256', $plainTextToken = \Str::random(20)),
                    'refreshToken'=>hash('sha256', $plainTextToken = \Str::random(20)),
                    'accessTokenExpiresAt'=>now()->addHours(1),
                    'refreshTokenExpiresAt'=>now()->addHours(2),
                ]);
                return $token ;
            }
            
        } catch (\Throwable $th) {
            return ["erreur"=>$th] ;
        }
    }
}
