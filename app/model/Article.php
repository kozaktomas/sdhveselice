<?php

namespace Sdh\Veselice\Model;

class Article
{

    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /** @var string */
    private $preheader;

    /** @var string */
    private $content;

    /** @var \DateTime */
    private $created;

    /**
     * Article constructor.
     * @param string $title
     * @param string $url
     * @param string $preheader
     * @param string $content
     * @param \DateTime $created
     */
    public function __construct(string $title, string $url, string $preheader, string $content, \DateTime $created)
    {
        $this->title = $title;
        $this->url = $url;
        $this->preheader = $preheader;
        $this->content = $content;
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getPreheader(): string
    {
        return $this->preheader;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }


}