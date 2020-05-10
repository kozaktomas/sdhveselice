<?php

declare(strict_types=1);

namespace Sdh\Veselice\Model;

use Nette;
use Nette\Application\IResponse;
use Suin\RSSWriter\Feed;

class XmlResponse implements IResponse
{
    private Feed $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse): void
    {
        $httpResponse->setContentType('text/xml', 'UTF-8');
        echo $this->feed;
    }
}