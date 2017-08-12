<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'email' => 'fvasquez@local.com',
            'username' => 'fvasquez',
            'first_name' => 'Faustino',
            'last_name' => 'Vasquez',
            'role'=>'admin',
        ]);
    }
}
