<?php

use App\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends FeatureTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_user_can_login_with_a_token()
    {
        //having
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        //when
        $this->visit("login/{$token->token}");

        //then
        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);

        $this->dontSeeInDatabase('tokens',[
            'id' => $token->id
        ]);

        $this->seePageIs('/');
    }

    public function test_a_user_cannot_login_with_an_invalid_token()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $invalidToken = str_random(60);

        //when
        $this->visit("login/{$invalidToken}");

        //then
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiro, por favor solicita otro');

        $this->seeInDatabase('tokens',[
           'id' => $token->id
        ]);
    }

    public function test_a_user_cannot_use_the_same_token_twice()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $token->login();

        Auth::logout();

        //when
        $this->visit("login/{$token->token}");

        //then
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiro, por favor solicita otro');

    }

    function test_the_token_expires_after_30_minutes()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        //simulamos que han pasado 30 mins
        Carbon::setTestNow(Carbon::parse('+31 minutes'));

        //when
        $this->visitRoute('login',['token' => $token->token]);

        //then
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiro, por favor solicita otro');
    }

    function test_the_token_is_case_sentitive()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        //when
        $this->visitRoute('login',['token' => strtolower($token->token)]);

        //then
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiro, por favor solicita otro');
    }

}
