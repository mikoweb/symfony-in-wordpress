<?php

namespace App\Module\Core\Application\Controller;

use App\Container;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractAppController extends AbstractController
{
    public function __construct()
    {
        $this->setContainer(Container::get()->getContainer());
    }

    protected function getParameter(string $name): array|bool|string|int|float|\UnitEnum|null
    {
        return Container::get()->getParameter($name);
    }

    protected function getTranslator(): TranslatorInterface
    {
        return $this->container->get('translator');
    }

    protected function addFlash(string $type, mixed $message): void
    {
        $this->container->get('session')->getFlashBag()->add($type, $message);
    }
}
