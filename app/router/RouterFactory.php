<?php

namespace App;

use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterFactory
{

    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter()
    {
        $router = new RouteList();
        $router[] = new Route('admin', "Sign:in");

        $router[] = new Route("rss", "Rss:feed");
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
