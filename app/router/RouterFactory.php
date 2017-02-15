<?php

namespace App;

use Nette\Application\BadRequestException;
use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Database\Context;


/**
 * Router factory.
 */
class RouterFactory
{

    /** @var Context */
    private $database;

    /** @var Cache */
    private $cache;

    /**
     * RouterFactory constructor.
     * @param Context $database
     * @param IStorage $storage
     */
    public function __construct(Context $database, IStorage $storage)
    {
        $this->database = $database;
        $this->cache = new Cache($storage, 'router-factory');
    }


    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter()
    {
        $database = $this->database;
        $cache = $this->cache;

        $router = new RouteList();
        $router[] = new Route('admin', "Sign:in");

        $router[] = new Route("rss", "Rss:feed");
        $router[] = new Route('clanek/<id>.html', [
            'presenter' => 'Article',
            'action' => 'detail',
            'id' => [
                Route::FILTER_IN => function ($id) {
                    $cacheKey = md5('clanek-slug-in' . $id);
                    $return = $this->cache->load($cacheKey);
                    if ($return === NULL) {
                        $row = $this->database->table('articles')->where('url', $id)->fetch();
                        if (!$row) {
                            throw new BadRequestException();
                        }
                        $return = $row['id'];
                        $this->cache->save($cacheKey, $return);
                    }
                    return $return;
                },
                Route::FILTER_OUT => function ($id) use ($database, $cache) {
                    $cacheKey = md5('clanek-slug-out' . $id);
                    $slug = $this->cache->load($cacheKey);
                    if ($slug === NULL) {
                        $row = $this->database->table('articles')->where('id', $id)->fetch();
                        if (!$row) {
                            throw new \LogicException("Article with ID {$id} not found.");
                        }

                        $slug = $row['url'];
                        $this->cache->save($cacheKey, $slug);
                    }
                    return $slug;
                },
            ],
        ]);
        $router[] = new Route('clanky.html?stranka=<vp-page>', "Article:list");
        $router[] = new Route("", "Document:default");
        $router[] = new Route("clenove-vyboru.html", "Document:vybor");
        $router[] = new Route("zavody-ve-veselici.html", "Document:zavody");
        $router[] = new Route("sportovni-druzstvo.html", "Document:sport");
        $router[] = new Route("kontakty.html", "Document:kontakt");
        $router[] = new Route("prace-s-mlazedi.html", "Document:mladez");
        $router[] = new Route("administrace/soubory", "File:default");
        $router[] = new Route("administrace/odhlasit", "Sign:out");
        $router[] = new Route("administrace/soubory/smazat/<file>/pom", "File:delete");
        $router[] = new Route("administrace/clanek/<id_article>/", "Article:admin");
        $router[] = new Route("administrace/clanek/smazat/<id_article>", "Article:delete");
        $router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
        return $router;
    }

}
