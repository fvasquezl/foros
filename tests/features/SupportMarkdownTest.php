<?php

class SupportMarkdownTest extends FeatureTestCase
{

    function test_the_post_content_support_markdown()
    {
        $importantText = 'Un texto muy importante';
        $post = $this->createPost([
            'content'=> "La primera parte del texto,**$importantText**. La ultima parte del texto"
        ]);

        $this->visit($post->url)
            ->seeInElement('strong',$importantText);
    }

    function test_the_code_in_the_post_is_scaped()
    {
        $xssAtack = "<script>alert('Malicius JS')</script>";
        $post = $this->createPost([
            'content' => "`$xssAtack`. Texto normal"
        ]);

        $this->visit($post->url)
            ->dontSee($xssAtack)
            ->seeText('Texto normal')
            ->seeText($xssAtack);
    }


    function test_xss_atack()
    {
        $xssAtack = "<script>alert('Malicius JS')</script>";
        $post = $this->createPost([
           'content' => "$xssAtack. Texto normal"
        ]);

        $this->visit($post->url)
            ->dontSee($xssAtack)
            ->seeText('Texto normal');
    }

    function test_xss_atack_with_html()
    {
        $xssAtack = "<img src='img.jpg'>";
        $post = $this->createPost([
            'content' => "$xssAtack. Texto normal"
        ]);

        $this->visit($post->url)
            ->dontSee($xssAtack);
    }
}
