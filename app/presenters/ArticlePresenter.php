<?php

namespace Sdh\Veselice\Presenters;

use App\Control\VisualPaginator;
use Nette\Application\BadRequestException;
use Sdh\Veselice\Model\ArticleNotFoundException;

class ArticlePresenter extends BasePresenter
{

    /**
     * Vykresluje seznam článků včetně stránkování
     */
    public function renderList()
    {
        $itemPerPage = 15;

        $articles = $this->articleList->getArticles();

        $vp = new VisualPaginator($this, 'vp');
        $vp->setItemsPerPage($itemPerPage);
        $vp->setItemsCount(count($articles));

        $articles = array_slice($articles, $vp->getPaginator()->getOffset(), $itemPerPage);
        $this->template->articles = $articles;
    }


    /**
     * Article detail
     * @param string $url
     * @throws BadRequestException
     */
    public function renderDetail($url)
    {
        try {
            $article = $this->articleList->getArticleByUrl((string)$url);
            $this->template->article = $article;
        } catch (ArticleNotFoundException $e) {
            throw new BadRequestException();
        }
    }

}