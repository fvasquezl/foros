<?php

class ShowPostTest extends TestCase
{

    function test_a_user_can_see_the_post_details()
    {
        //having
        $user = $this->defaultUser([
            'name' => 'Faustino Vasquez',
        ]);


        $post = factory(\App\Post::class)->make([
           'title' => 'Como Instalar Laravel',
            'content' => 'Este es el contenido del post'
        ]);

        $user->posts()->save($post);

        //when
        $this->visit(route('posts.show',$post))
            ->seeInElement('h1',$post->title)
            ->see($post->content)
            ->see($user->name);

    }
}
