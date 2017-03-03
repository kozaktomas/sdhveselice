<?php

use Tester\Assert;
use Tester\TestCase;

require __DIR__ . "/../../bootstrap.php";

class ArticleListTests extends TestCase
{

    public function testGetArticles()
    {
        $al = new \Sdh\Veselice\Model\ArticleList(
            __DIR__ . "/articles",
            new \Nette\Caching\Storages\DevNullStorage()
        );

        $articles = $al->getArticles();
        Assert::count(2, $articles);

        $article1 = $articles[0];
        Assert::equal('Testing Title 2', $article1->getTitle());
        Assert::equal('test2', $article1->getUrl());
        Assert::contains('Preheader text 2', $article1->getPreheader());
        Assert::contains('This is content 2.', $article1->getContent());
        Assert::equal('2017-03-03', $article1->getCreated()->format('Y-m-d'));

        $article2 = $articles[1];
        Assert::equal('Testing Title 1', $article2->getTitle());
        Assert::equal('test1', $article2->getUrl());
        Assert::contains('Preheader text 1', $article2->getPreheader());
        Assert::contains('This is content 1.', $article2->getContent());
        Assert::equal('2017-03-02', $article2->getCreated()->format('Y-m-d'));
    }

}

$tests = new ArticleListTests();
$tests->run();