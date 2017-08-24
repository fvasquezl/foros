<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_a_user_can_logout()
    {
        $user = $this->defaultUser([
            'first_name' => 'Faustino',
            'last_name' => 'Vasquez'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->clickLink('Faustino Vasquez')
                ->clickLink('Cerrar Sesion')
                ->assertGuest();
        });
    }
}
