<?php

declare(strict_types=1);

namespace Sdh\Veselice\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Sdh\Veselice\Model\ArticleList;
use Sdh\Veselice\Model\StaticFiles;
use Tracy\Debugger;

/**
 * @property Template $template
 */
abstract class BasePresenter extends Presenter
{

    protected ArticleList $articleList;

    public function __construct(ArticleList $articleList)
    {
        parent::__construct();
        $this->articleList = $articleList;
    }

    protected function startup()
    {
        parent::startup();

        $articles = array_slice($this->articleList->getArticles(), 0, 3);
        $this->template->news = $articles;
        $this->template->header = rand(1, 4);

        $this->template->staticDebug = \getenv("DEV") === "1";
        $this->template->cssVersion = StaticFiles::CSS_VERSION;
        $this->template->jsVersion = StaticFiles::JS_VERSION;
    }

}
