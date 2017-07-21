<?php

class ShowPostTest extends FeatureTestCase
{

    function test_a_user_can_see_the_post_details()
    {
        //having
        $user = $this->defaultUser([
            'name' => 'Faustino Vasquez',
        ]);

        $post = $this->createPost([
           'title' => 'Como Instalar Laravel',
            'content' => 'Este es el contenido del post',
            'user_id' => $user->id,
        ]);


        //when
        $this->visit($post->url)
            ->seeInElement('h1',$post->title)
            ->see($post->content)
            ->see('Faustino Vasquez');
    }

    function test_old_url_are_redirected()
    {
        //having
        $post = $this->createPost([
            'title' => 'Old Title',
        ]);

        $url = $post->url;
        $post->update(['title'=>'New title']);

        $this->visit($url)
            ->seePageIs($post->url);
    }


}
