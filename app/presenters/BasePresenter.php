<?php

namespace Sdh\Veselice\Presenters;


use Nette\Application\UI\Presenter;
use Sdh\Veselice\Model\ArticleList;
use Sdh\Veselice\Model\StaticFiles;
use Tracy\Debugger;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{

    /** @var ArticleList @inject */
    public $articleList;

    protected function startup()
    {
        parent::startup();
        $user = false;
        if ($this->getUser()->isLoggedIn()) {
            $user = true;
        }
        $this->template->user = $user;

        $articles = array_slice($this->articleList->getArticles(), 0 , 3);
        $this->template->news = $articles;
        $this->template->header = rand(1, 4);

        $this->template->staticDebug = !Debugger::$productionMode;
        $this->template->cssVersion = StaticFiles::CSS_VERSION;
        $this->template->jsVersion = StaticFiles::JS_VERSION;
    }

}
