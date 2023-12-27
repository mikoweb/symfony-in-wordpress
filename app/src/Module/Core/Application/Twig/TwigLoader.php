<?php

namespace App\Module\Core\Application\Twig;

use App\Container;
use App\Module\WP\Admin\Application\Twig\Extension\AdminRouteExtension;
use App\Path;
use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

final class TwigLoader
{
    private bool $load = false;
    private Environment $twig;

    public function __construct(
        private readonly SessionInterface $session,
        private readonly TranslatorInterface $translator,
        private readonly AdminRouteExtension $adminRouteExtension
    ) {}

    public function load(): Environment
    {
        if (!$this->load) {
            $loader = new FilesystemLoader([
                Path::getTemplatesPath(),
                Path::getVendorPath('symfony/twig-bridge/Resources/views/Form')
            ], Path::getAppPath());
            $debug = Container::get()->getParameter('twig_debug');
            $this->twig = new Environment($loader, [
                'cache' => $debug ? false : Path::getCachePath('twig'),
                'debug' => $debug,
            ]);

            $request = Request::createFromGlobals();

            $this->twig->addGlobal('flash_bag', $this->session->getFlashBag());
            $this->twig->addGlobal('request_query', $request->query);

            $formEngine = new TwigRendererEngine(['bootstrap_3_layout.html.twig'], $this->twig);
            $this->twig->addRuntimeLoader(new FactoryRuntimeLoader([
                FormRenderer::class => function () use ($formEngine) {
                    return new FormRenderer($formEngine);
                },
            ]));

            $this->twig->addExtension(new TranslationExtension($this->translator));
            $this->twig->addExtension(new DebugExtension());
            $this->twig->addExtension(new DumpExtension(new VarCloner()));
            $this->twig->addExtension(new FormExtension($this->translator));
            $this->twig->addExtension($this->adminRouteExtension);

            $this->load = true;
        }

        return $this->twig;
    }
}
