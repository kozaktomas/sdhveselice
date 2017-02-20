<?php

namespace App\Presenters;

use App\StaticFiles;
use Nette,
    App\Article;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /** @var Nette\Database\Table\Selection */
    protected $articleTable;

    public function injectArticle(Article $article)
    {
        $this->articleTable = $article->getTable();
    }

    protected function startup()
    {
        parent::startup();
        $user = false;
        if ($this->getUser()->isLoggedIn()) {
            $user = true;
        }
        $this->template->user = $user;

        $articles = $this->articleTable->order('id DESC')->limit(3)->fetchAll();
        $this->template->news = $articles;
        $this->template->header = rand(1, 4);
        $this->template->staticDebug = StaticFiles::DEBUG_MODE;
    }

}
