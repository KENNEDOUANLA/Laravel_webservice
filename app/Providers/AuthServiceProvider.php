<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Sanctum\Token;
use Laravel\Sanctum\Sanctum;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //Sanctum::usePersonalAccessTokenModel(Token::class);
        // Sanctum::authenticateAccessTokensUsing(
        //     static function (Token $accessToken, bool $is_valid) {
        //         // your logic here
        //     }
        // );
        //
    }
}
