<?php

use App\Comment;
use App\Notifications\PostCommented;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\Messages\MailMessage;

class PostCommentedTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     */
    function test_it_builds_a_mail_message()
    {
        $post = new Post([
            'title' => 'Titulo del post',
        ]);
        $author = new User([
            'first_name' => 'Faustino',
            'last_name' => 'Vasquez'
        ]);

        $comment = new Comment;
        $comment->post = $post;
        $comment->user = $author;

        $notification = new PostCommented($comment);
        $subscriber = new User();
        $message = $notification->toMail($subscriber);

        $this->assertInstanceOf(MailMessage::class,$message);

        $this->assertSame(
            'Nuevo comentario en: Titulo del post',
            $message->subject
        );

        $this->assertSame(
            'Faustino Vasquez escribio un comentario en: Titulo del post',
            $message->introLines[0]
        );

        $this->assertSame($comment->post->url,$message->actionUrl);
    }
}
