<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends FeatureTestCase
{

    function test_basic_example()
    {
        $user = factory(\App\User::class)->create([
            'name' => 'Faustino Vasquez',
            'email'=>'admin@foro.dev',
        ]);

        $this->actingAs($user,'api')
             ->visit('api/user')
             ->see('Faustino Vasquez')
             ->see('admin@foro.dev');
    }
}
