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
            $this->title($article);
            $this->preheader($article);
        }
    }

    private function title(Article $article)
    {
        Assert::true(Strings::length($article->getTitle()) <= 100, "Title is too long in: " . $article->getTitle());
        Assert::false((bool)preg_match("/<[^<]+>/", $article->getTitle(), $m), "Title contains HTML in: " . $article->getTitle());
    }

    private function preheader(Article $article)
    {
        Assert::true(Strings::length($article->getPreheader()) < 220, "Preheader is too long in: " . $article->getTitle());
        Assert::false((bool)preg_match("/<[^<]+>/", $article->getPreheader(), $m), "Preheader contains HTML in: " . $article->getTitle());
        Assert::true(
            "." === Strings::substring($article->getPreheader(), -1)
            || "" === $article->getPreheader(),
                "Preheader have to ends with dot or have to be empty in: " . $article->getTitle());
    }


}

$tests = new ArticleContentTests($container);
$tests->run();