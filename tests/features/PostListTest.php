<?php

use App\Category;
use App\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostListTest extends FeatureTestCase
{

    public function test_a_user_can_see_the_posts_list_and_go_to_the_details()
    {
        $post = $this->createPost([
            'title' => 'Debo usar laravel 5.3 o 5.1 LTS?'
        ]);
        $this->visit('/')
            ->seeInElement('h1','Posts')
            ->see($post->title)
            ->click($post->title)
            ->seePageIs($post->url);
    }

    function test_a_user_can_see_posts_filtered_by_category()
    {
        $laravel = factory(Category::class)->create([
            'name' =>'Laravel','slug' =>'laravel'
        ]);
        $vue = factory(Category::class)->create([
            'name' =>'Vue','slug' =>'vue'
        ]);
        $laravelPost = factory(Post::class)->create([
            'title' => 'Post de Laravel',
            'category_id'=>$laravel->id
        ]);
        $vuePost = factory(Post::class)->create([
            'title' => 'Post de Vue.js',
            'category_id'=>$vue->id
        ]);

        $this->visit('/')
            ->see($laravelPost->title)
            ->see($vuePost->title)
            ->within('.categories',function(){
                $this->click('Laravel');
            })
            ->seeInElement('h1','Post de Laravel')
            ->see($laravelPost->title)
            ->dontSee($vuePost->title);
    }

    function test_a_user_can_see_posts_filtered_by_status(){
        $pendingPost =factory(Post::class)->create([
            'title' => 'Post pendiente',
            'pending' => true,
        ]);
        $completedPost = factory(Post::class)->create([
            'title' => 'Post completado',
            'pending' => false
        ]);

        $this->visitRoute('posts.pending')
            ->see($pendingPost->title)
            ->dontSee($completedPost->title);
        $this->visitRoute('posts.completed')
            ->see($completedPost->title)
            ->dontSee($pendingPost->title);
    }


    function test_the_post_are_paginated()
    {
        $first =factory(Post::class)->create([
           'title' => 'Post mas antiguo',
            'created_at' => Carbon::now()->subDay(2)
        ]);
        factory(Post::class)->times(15)->create([
            'created_at' => Carbon::now()->subDay()
        ]);

        $last =factory(Post::class)->create([
            'title' => 'Post mas reciente',
            'created_at'=> Carbon::now(),
        ]);

        $this->visit('/')
            ->see($last->title)
            ->dontSee($first->title)
            ->click(2)
            ->see($first->title)
            ->dontSee($last->title);
    }

}
