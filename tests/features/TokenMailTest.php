<?php

use App\Token;
use App\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\DomCrawler\Crawler;

class TokenMailTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function it_sends_a_link_with_the_token()
    {
        $user = new User([
           'first_name' => 'Faustino',
            'last_name' => 'Vasquez',
            'email' => 'fvasquez@local.com'
        ]);
        $token = new Token([
            'token' => 'this-is-a-token',
            'user' => $user
        ]);

        $this->open(new \App\Mail\TokenMail($token))
            ->seeLink($token->url, $token->url);

    }

    protected function open(\Illuminate\Mail\Mailable $mailable)
    {
        $transport = Mail::getSwiftMailer()->getTransport();
        $transport ->flush();

        Mail::send($mailable);

        $message =$transport->messages()->first();

        $this->crawler = new Crawler($message->getBody());

        return $this;
    }
}
