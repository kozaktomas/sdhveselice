<?php

declare(strict_types=1);

namespace Sdh\Veselice\Model;

use DateTime;

class Article
{
    private string $title;

    private string $url;

    private string $image;

    private string $preheader;

    private string $content;

    private DateTime $created;

    public function __construct(string $title, string $url, string $image, string $preheader, string $content, DateTime $created)
    {
        $this->title = $title;
        $this->url = $url;
        $this->image = $image;
        $this->preheader = $preheader;
        $this->content = $content;
        $this->created = $created;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getPreheader(): string
    {
        return $this->preheader;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }
}