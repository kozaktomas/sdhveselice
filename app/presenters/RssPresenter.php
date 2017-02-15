<?php

namespace App\Presenters;


use App\XmlResponse;
use Nette\Utils\Strings;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

class RssPresenter extends BasePresenter
{

    public function actionFeed()
    {
        $feed = new Feed();
        $channel = new Channel();
        $channel
            ->title("SDH Veselice")
            ->description("Rss feed pro novinky z webu SDH Veselice")
            ->url($this->link("//Document:default"))
            ->appendTo($feed);

        $articles = $this->articleTable->order('id DESC')->limit(10);
        foreach ($articles as $article) {
            $text = Strings::truncate(strip_tags($article->text), 260);
            $item = new Item();
            $item
                ->title($article->title)
                ->description($text)
                ->url($this->link("//Article:detail", ['url' => $article->url]))
                ->appendTo($channel);
        }
        $this->sendResponse(new XmlResponse($feed));
        $this->terminate();
    }

}