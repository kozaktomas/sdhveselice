<?php

namespace Sdh\Veselice\Model;

use Latte\Engine;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Utils\DateTime;

class ArticleList
{

    /** @var string */
    private $directory;

    /** @var Cache */
    private $cache;


    /**
     * ArticleList constructor.
     * @param null|string $directory
     * @param IStorage $storage
     */
    public function __construct(?string $directory = null, IStorage $storage)
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

            $title = trim($latte->renderToString($file, $defaultVariables, 'title'));
            $preheader = $latte->renderToString($file, $defaultVariables, 'preheader');
            $content = $latte->renderToString($file, $defaultVariables, 'content');
            $created = new DateTime($matches[1]);
            $url = $matches[2];

            $articles[] = new Article(
                $title,
                $url,
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

    private function getArticleFiles()
    {
        $files = glob($this->directory . DIRECTORY_SEPARATOR . "*");
        return $files;
    }

}