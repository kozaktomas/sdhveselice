<?php

declare(strict_types=1);

namespace Sdh\Veselice\Presenters;

use Nette\Application\BadRequestException;
use Sdh\Veselice\Control\VisualPaginator\VisualPaginator;
use Sdh\Veselice\Model\ArticleNotFoundException;

class ArticlePresenter extends BasePresenter
{
    /** Number of articles shown in list */
    private const ITEMS_PER_PAGE = 15;

    public function renderList(): void
    {
        $articles = $this->articleList->getArticles();

        $vp = new VisualPaginator();
        $vp->setItemsPerPage(self::ITEMS_PER_PAGE);
        $vp->setItemsCount(count($articles));

        $this->addComponent($vp, 'vp');

        $articles = array_slice($articles, $vp->getPaginator()->getOffset(), self::ITEMS_PER_PAGE);
        $this->template->articles = $articles;
    }

    public function renderDetail(string $url): void
    {
        try {
            $article = $this->articleList->getArticleByUrl((string)$url);
            $this->template->article = $article;
        } catch (ArticleNotFoundException $e) {
            throw new BadRequestException();
        }
    }
}