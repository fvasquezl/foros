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
            'name' =>'Categoria de Laravel','slug' =>'laravel'
        ]);
        $vue = factory(Category::class)->create([
            'name' =>'Vue.js','slug' =>'vue'
        ]);
        $pendingLaravelPost = factory(Post::class)->create([
            'title' => 'Post pendiente de Laravel',
            'category_id'=>$laravel->id,
            'pending' => true,
        ]);
        $pendingVuePost = factory(Post::class)->create([
            'title' => 'Post pendiente de Vue.js',
            'category_id'=>$vue->id,
            'pending' => true,
        ]);
        $completedPost = factory(Post::class)->create([
            'title' => 'Post completado',
            'pending' => false,
        ]);

        $this->visitRoute('posts.index')
            ->click('Posts pendientes')
            ->click('Categoria de Laravel')
            ->seePageIs('posts-pendientes/laravel')
            ->see($pendingLaravelPost->title)
            ->dontSee($completedPost->title)
            ->dontSee($pendingVuePost->title);
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
