<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $primaryKey = 'uid';
    protected $keyType = 'string';

    protected $fillable = [
        'uid',
        'accessToken',
        'accessTokenExpiresAt',
        'refreshToken',
        'refreshTokenExpiresAt',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'uid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"uid","uid");
    }

    public static function isValid($access_token,$token_type="accessToken"){
        $token=Token::where($token_type,$access_token)->get()[0]?? null;
        if($token)
            if ($token_type=="accessToken")
                $token=$token->accessTokenExpiresAt >= now() ?  $token:  null;
            else
                $token=$token->refreshTokenExpiresAt >= now() ?  $token:  null;
              
        return $token;
    }
}
