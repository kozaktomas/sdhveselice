<?php

use Nette\Utils\Strings;
use Sdh\Veselice\Model\Article;
use Sdh\Veselice\Model\ArticleList;
use Tester\Assert;

$container = require __DIR__ . "/../bootstrap.php";

/**
 * @testCase
 */
class ArticleContentTests extends \Tester\TestCase
{

    /** @var \Nette\DI\Container */
    private $container;

    /**
     * @param \Nette\DI\Container $container
     */
    public function __construct(\Nette\DI\Container $container)
    {
        $this->container = $container;
    }

    public function testArticles()
    {
        /** @var ArticleList $articleList */
        $articleList = $this->container->getByType(ArticleList::class);
        foreach ($articleList->getArticles() as $article) {
            $this->preheader($article);
        }
    }

    private function preheader(Article $article)
    {
        Assert::true(Strings::length($article->getPreheader()) < 220, "Preheader is too long in: " . $article->getTitle());
        Assert::false((bool)preg_match("/<[^<]+>/", $article->getPreheader(), $m), "Preheader contains HTML in: " . $article->getTitle());
    }


}

$tests = new ArticleContentTests($container);
$tests->run();