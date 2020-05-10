<?php

declare(strict_types=1);

namespace Sdh\Veselice\Presenters;

use Sdh\Veselice\Model\Article;
use Sdh\Veselice\Model\XmlResponse;
use Nette\Utils\Strings;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

class RssPresenter extends BasePresenter
{

    private const ARTICLES_COUNT_IN_RSS = 10;

    public function actionFeed(): void
    {
        $feed = new Feed();
        $channel = new Channel();
        $channel
            ->title("SDH Veselice")
            ->description("Rss feed pro novinky z webu SDH Veselice")
            ->url($this->link("//Document:default"))
            ->appendTo($feed);

        /** @var Article[] $articles */
        $articles = $this->articleList->getArticles();
        $articles = array_slice($articles, 0, self::ARTICLES_COUNT_IN_RSS);
        foreach ($articles as $article) {
            $text = Strings::truncate(strip_tags($article->getContent()), 260);
            $item = new Item();
            $item
                ->title($article->getTitle())
                ->description($text)
                ->url($this->link("//Article:detail", ['url' => $article->getUrl()]))
                ->appendTo($channel);
        }
        $this->sendResponse(new XmlResponse($feed));
    }

}