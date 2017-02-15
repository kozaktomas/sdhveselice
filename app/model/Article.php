<?php

namespace App;

use Nette\Database\Context;

class Article
{

    const TABLE_NAME = 'articles';

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return \Nette\Database\Table\Selection
     */
    public function getTable()
    {
        return $this->context->table(self::TABLE_NAME);
    }


} 