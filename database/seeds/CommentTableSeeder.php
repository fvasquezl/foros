<?php

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{

    public function run()
    {
        $users = User::query()->select('id')->get();
        $posts = Post::query()->select('id')->get();
        for ($i=0;$i<250;++$i){
            $comment = factory(Comment::class)->create([
                'user_id' => $users->random()->id,
                'post_id' => $posts->random()->id
            ]);

            if(rand(0,1)){
                $comment->markAsAnswer();
            }
        }

    }
}
