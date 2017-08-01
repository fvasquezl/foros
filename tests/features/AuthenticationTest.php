<?php

use App\Token;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
}
