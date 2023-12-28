<?php

namespace App\Module\WP\Site\Application\Routing;

use Symfony\Component\HttpFoundation\Request;
use function Symfony\Component\String\u;

final class RequestFactory
{
    public function createRequest(): Request
    {
        $request = Request::createFromGlobals();
        $newUri = u($request->getRequestUri())->trimPrefix('/index.php/')->ensureStart('/')->toString();
        $_SERVER['REQUEST_URI'] = $newUri;
        $newRequest = Request::createFromGlobals();
        $_SERVER['REQUEST_URI'] = $request->getRequestUri();

        return $newRequest;
    }
}
