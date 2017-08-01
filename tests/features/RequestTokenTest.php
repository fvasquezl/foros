<?php

use App\Token;
use Illuminate\Support\Facades\Mail;

class RequestTokenTest extends FeatureTestCase
{

    public function test_a_guest_user_can_request_a_token()
    {
        //having
        Mail::fake();
        $user = $this->defaultUser(['email'=>'admin@local.com']);

        //when
        $this->visitRoute('token')
            ->type('admin@local.com','email')
            ->press('Solicitar token');

        //then: a new token is created in the database
        $token = Token::where('user_id',$user->id)->first();
        $this->assertNotNull($token, 'A token was not created');

        //And sent to de user
        Mail::assertSentTo($user, \App\Mail\TokenMail::class, function($mail) use($token){
            return $mail->token->id === $token->id;
        });
        $this->dontSeeIsAuthenticated();

        $this->see('Enviamos a tu email un enlace para que inicies sesion');

    }

    public function test_a_guest_user_can_request_a_token_without_an_email()
    {
        //having
        Mail::fake();

        //when
        $this->visitRoute('token')
            ->press('Solicitar token');

        //then: a new token is Not created in the database
        $token = Token::first();
        $this->assertNull($token, 'A token was created');

        //And sent to de user
        Mail::assertNotSent(\App\Mail\TokenMail::class);
        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio.',
        ]);
    }

    public function test_a_guest_user_can_request_a_token_an_invalid_email()
    {
        //when
        $this->visitRoute('token')
            ->type('fvasquez','email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'correo electrónico no es un correo válido',
        ]);
    }

    public function test_a_guest_user_can_request_a_token_with_a_non_existent_email()
    {
        $user = $this->defaultUser(['email'=>'admin@local.com']);
        //when
        $this->visitRoute('token')
            ->type('fvasquez@local.com','email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Este correo electrónico es inválido.',
        ]);
    }
}
