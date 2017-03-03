<?php

namespace Sdh\Veselice\Model;

use Nette;
use Nette\Application\IResponse;
use Suin\RSSWriter\Feed;

class XmlResponse implements IResponse
{

    /** @var Feed */
    private $feed;

    /**
     * XmlResponse constructor.
     * @param Feed $feed
     */
    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * @param Nette\Http\IRequest $httpRequest
     * @param Nette\Http\IResponse $httpResponse
     */
    function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
    {
        $httpResponse->setContentType('text/xml', 'UTF-8');
        echo $this->feed;
    }
}