<?php

declare(strict_types=1);

namespace Sdh\Veselice\Router;

use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;

class RouterFactory
{
    public function createRouter(): RouteList
    {
        $router = new RouteList();
        $router[] = new Route("rss", "Rss:feed");
        $router[] = new Route('clanky.html?stranka=<vp-page>', "Article:list");
        $router[] = new Route('clanek/<url>.html', "Article:detail");
        $router[] = new Route("", "Document:default");
        $router[] = new Route("clenove-vyboru.html", "Document:vybor");
        $router[] = new Route("zavody-ve-veselici.html", "Document:zavody");
        $router[] = new Route("sportovni-druzstvo.html", "Document:sport");
        $router[] = new Route("kontakty.html", "Document:kontakt");
        $router[] = new Route("prace-s-mlazedi.html", "Document:mladez");
        return $router;
    }
}
