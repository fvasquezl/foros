<?php

namespace Tests;

use App\Post;
use App\User;

trait TestsHelper
{
    /**
     * @var \App\User
     */
    protected $defaultUser;


    /**
     * @return mixed
     */
    public function defaultUser(array $attributes=[])
    {
        if($this->defaultUser){
            return $this->defaultUser;
        }
        return $this->defaultUser = factory(User::class)->create($attributes);
    }

    protected function createPost(array $attributes = [])
    {
        return factory(Post::class)->create($attributes);
    }

    protected function anyone(array $attributes = [])
    {
        return factory(User::class)->create($attributes);
    }

    protected function actingAsAnyone(array $attributes = [])
    {
        $user = $this->anyone($attributes);
        $this->actingAs($user);
        return $user;
    }

}