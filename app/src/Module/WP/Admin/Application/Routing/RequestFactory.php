<?php

namespace App\Module\WP\Admin\Application\Routing;

use Symfony\Component\HttpFoundation\Request;

final class RequestFactory
{
    public function createRequest(): Request
    {
        $request = Request::createFromGlobals();
        $newUri = $request->query->get('route', '/');
        $_SERVER['REQUEST_URI'] = $newUri;
        $newRequest = Request::createFromGlobals();
        $_SERVER['REQUEST_URI'] = $request->getRequestUri();

        $newRequest->query->remove('page');
        parse_str(parse_url($newUri)['query'] ?? '', $newQuery);

        foreach ($newQuery as $key => $value) {
            $newRequest->query->set($key, $value);
        }

        return $newRequest;
    }
}
