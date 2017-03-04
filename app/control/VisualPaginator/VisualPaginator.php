<?php

namespace App\Control;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class VisualPaginator extends Control
{
    
    /** @var Paginator */
    private $paginator;

    /** @persistent */
    public $page = 1;

    /**
     * VisualPaginator constructor.
     * @param Control $parent
     * @param string $name
     */
    public function __construct(Control $parent, string $name)
    {
        $this->paginator = new Paginator();
        parent::__construct($parent, $name);
    }

    /**
     * @param int $count
     */
    public function setItemsCount(int $count)
    {
        $this->paginator->itemCount = $count;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage)
    {
        $this->paginator->itemsPerPage = $itemsPerPage;
    }

    /**
     * Renders paginator.
     * @return void
     */
    public function render()
    {
        $page = $this->paginator->page;
        if ($this->paginator->pageCount < 2) {
            $steps = array($page);
        } else {
            $arr = range(max($this->paginator->firstPage, $page - 3), min($this->paginator->lastPage, $page + 3));
            $count = 4;
            $quotient = ($this->paginator->pageCount - 1) / $count;
            for ($i = 0 ; $i <= $count ; $i++) {
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

    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * Loads state informations.
     * @param array
     * @return void
     */
    public function loadState(array $params)
    {
        parent::loadState($params);
        if (isset($params['page'])) {
            $this->paginator->page = $params['page'];
        }
    }

}