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
        $this->visit($post->url)
            ->seeInElement('h1',$post->title)
            ->see($post->content)
            ->see($user->name);

    }

    function test_old_url_are_redirected()
    {
        //having
        $user = $this->defaultUser();

        $post = factory(\App\Post::class)->make([
            'title' => 'Old Title',
        ]);
        $user->posts()->save($post);

        $url = $post->url;
        $post->update(['title'=>'New title']);

        $this->visit($url)
            ->seePageIs($post->url);
    }


}
