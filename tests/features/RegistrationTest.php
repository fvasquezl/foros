<?php

use App\{
    Mail\TokenMail, User, Token
};
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{

    public function test_a_user_can_create_an_account()
    {
        Mail::fake();
        $this->visitRoute('register')
            ->type('admin@local.net','email')
            ->type('fvasquez','username')
            ->type('Faustino','first_name')
            ->type('Vasquez','last_name')
            ->press('Registrate');

        $this->seeInDatabase('users',[
            'email'=>'admin@local.net',
            'username'=>'fvasquez',
            'first_name'=>'Faustino',
            'last_name' => 'Vasquez'
        ]);
        $user = User::first();

        $this->seeInDatabase('tokens',[
            'user_id' => $user->id
        ]);
        $token = Token::where('user_id',$user->id)->first();
        $this->assertNotNull($token);

        Mail::assertSentTo($user,TokenMail::class, function($mail) use($token){
            return $mail->token->id == $token->id;
        });

        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu email un enlace para que inicies sesion');
    }

    public function test_user_create_an_account_post_validation()
    {
        $this->visitRoute('register')
            ->press('Registrate')
            ->seePageIs(route('register'))
            ->seeErrors([
                'email' => 'El campo correo electrónico es obligatorio',
                'username' => 'El campo usuario es obligatorio.',
                'first_name' => 'El campo nombre es obligatorio.',
                'last_name' => 'el campo apellido es obligatorio.',
            ]);
    }
}
