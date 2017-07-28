<?php

class SubscribePostTest extends FeatureTestCase
{

    public function test_a_user_can_subscribe_to_a_post()
    {
        //having
        $post = $this->createPost();

        $user = factory(\App\User::class)->create();

        $this->actingAs($user);

        //when
        $this->visit($post->url)
            ->press('Suscribirse al post');

        //then
        $this->seeInDatabase('subscriptions',[
            'user_id' =>$user->id,
            'post_id' =>$post->id,
        ]);
        $this->seePageIs($post->url)
            ->dontSee('Suscribirse al post');
    }

    function test_a_user_can_unsubscribe_from_a_post()
    {
        //having
        $post = $this->createPost();

        $user = factory(\App\User::class)->create();

        $this->actingAs($user);

        $user->subscribeTo($post);
        $this->actingAs($user);

        //When
        $this->visit($post->url)
            ->dontSee('Suscribirse al post')
            ->press('Desuscribirse del post');

        $this->dontSeeInDatabase('subscriptions',[
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
        $this->seePageIs($post->url);

    }
}
