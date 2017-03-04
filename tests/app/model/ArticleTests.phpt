<?php

use Tester\Assert;
use Tester\TestCase;

require __DIR__ . "/../../bootstrap.php";


class ArticleTests extends TestCase
{

    public function testEntity()
    {
        $article = new \Sdh\Veselice\Model\Article(
            "This is title",
            "this-is-title.html",
            "image-path.png",
            "Preheader text",
            "Awesome content",
            new DateTime('2017-03-03')
        );

        Assert::equal("This is title", $article->getTitle());
        Assert::equal("this-is-title.html", $article->getUrl());
        Assert::equal("image-path.png", $article->getImage());
        Assert::equal("Preheader text", $article->getPreheader());
        Assert::equal("Awesome content", $article->getContent());
        Assert::equal("2017-03-03", $article->getCreated()->format('Y-m-d'));
    }

}

$tests = new ArticleTests();
$tests->run();