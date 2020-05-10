<?php

declare(strict_types=1);

namespace Sdh\Veselice\Model;

use Latte\Engine;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;

class ArticleList
{

    private string $directory;

    private Cache $cache;

    public function __construct(?string $directory, IStorage $storage)
    {
        if (is_null($directory)) {
            $this->directory = __DIR__ . "/../articles";
        } else {
            $this->directory = $directory;
        }

        $this->cache = new Cache($storage);
    }

    /**
     * @return Article[]
     * @throws \Exception
     */
    public function getArticles(): array
    {
        $latte = new Engine();
        $latte->setTempDirectory(__DIR__ . "/../../temp/cache");
        $defaultVariables = [];

        $articles = [];
        $files = $this->getArticleFiles();
        foreach ($files as $file) {
            $splFile = new \SplFileInfo($file);
            if (!preg_match('/(\d{4}\-\d{2}\-\d{2})\-([0-9a-zA-Z-]+)\.latte/', $splFile->getFilename(), $matches)) {
                continue;
            }

            $title = Strings::trim($latte->renderToString($file, $defaultVariables, 'title'));
            $image = Strings::trim($latte->renderToString($file, $defaultVariables, 'image'));
            $preheader = Strings::trim($latte->renderToString($file, $defaultVariables, 'preheader'));
            $content = $latte->renderToString($file, $defaultVariables, 'content');
            $created = new DateTime($matches[1]);
            $url = $matches[2];

            $articles[] = new Article(
                $title,
                $url,
                $image,
                $preheader,
                $content,
                $created
            );
        }

        usort($articles, function (Article $a, Article $b) {
            if ($a->getCreated()->format('U') < $b->getCreated()->format('U'))
                return true;
            return false;
        });

        return $articles;
    }

    /**
     * @param string $url
     * @return Article
     * @throws ArticleNotFoundException
     */
    public function getArticleByUrl(string $url): Article
    {
        $articles = $this->getArticles();
        foreach ($articles as $article) {
            if ($article->getUrl() === $url)
                return $article;
        }

        throw new ArticleNotFoundException("Article with url '{$url}' not found.");
    }

    /**
     * @return string[]
     * @throws ArticleNotFoundException
     */
    private function getArticleFiles(): array
    {
        $files = glob($this->directory . DIRECTORY_SEPARATOR . "*");
        if (!$files) {
            throw new ArticleNotFoundException('Could not find any files in article directory');
        }
        return $files;
    }

}