<?php

namespace App\Module\WP\Site\Application\Routing;

use App\Container;
use App\Path;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

final class SiteRoutingLoader
{
    private bool $loaded = false;
    private Request $request;
    private HttpKernel $kernel;
    private RouterInterface $router;
    private UrlMatcherInterface $matcher;

    public function __construct(
        private readonly RequestFactory $requestFactory
    ) {}

    public function load(): RouterInterface
    {
        if (!$this->loaded) {
            $container = Container::get()->getContainer();
            $this->router = new Router(
                loader: new YamlFileLoader(new FileLocator(Path::getConfigPath('routes'))),
                resource: 'site.yaml',
                defaultLocale: $container->getParameter('locale')
            );

            $this->request = $this->requestFactory->createRequest();

            $compiledRoutes = (new CompiledUrlMatcherDumper($this->router->getRouteCollection()))->getCompiledRoutes();
            $this->matcher = new CompiledUrlMatcher($compiledRoutes, new RequestContext());

            $dispatcher = new EventDispatcher();
            $dispatcher->addSubscriber(new RouterListener($this->matcher, new RequestStack()));

            $controllerResolver = new ControllerResolver();
            $argumentResolver = new ArgumentResolver();

            $this->kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

            $this->loaded = true;
        }

        return $this->router;
    }

    public function getMatcher(): UrlMatcherInterface
    {
        $this->load();

        return $this->matcher;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function handleRequest(): Response
    {
        return $this->kernel->handle($this->request, catch: false);
    }
}
