<?php

declare(strict_types=1);

namespace Sdh\Veselice\Control\VisualPaginator;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Paginator;

/**
 * @property Template $template
 */
class VisualPaginator extends Control
{

    private Paginator $paginator;

    /** @persistent */
    public string $page = "1";

    public function __construct()
    {
        $this->paginator = new Paginator();
    }

    public function setItemsCount(int $count): void
    {
        $this->paginator->itemCount = $count;
    }

    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->paginator->itemsPerPage = $itemsPerPage;
    }

    public function render(): void
    {
        $page = $this->paginator->page;
        if ($this->paginator->pageCount < 2) {
            $steps = array($page);
        } else {
            $arr = range(max($this->paginator->firstPage, $page - 3), min($this->paginator->lastPage, $page + 3));
            $count = 4;
            $quotient = ($this->paginator->pageCount - 1) / $count;
            for ($i = 0; $i <= $count; $i++) {
                $arr[] = round($quotient * $i) + $this->paginator->firstPage;
            }
            sort($arr);
            $steps = array_values(array_unique($arr));
        }

        $this->template->steps = $steps;
        $this->template->paginator = $this->paginator;
        $this->template->setFile(dirname(__FILE__) . '/template.latte');
        $this->template->render();
    }

    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    /**
     * @param string[] $params
     * @throws \Nette\Application\BadRequestException
     */
    public function loadState(array $params): void
    {
        parent::loadState($params);
        if (isset($params['page'])) {
            $this->paginator->page = (int)$params['page'];
        }
    }
}