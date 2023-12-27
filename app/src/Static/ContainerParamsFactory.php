<?php

namespace App\Static;

use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ContainerParamsFactory
{
    public static function setupFromWP(ContainerBuilder $builder): void
    {
        self::setLocale($builder);
    }

    public static function setupFromEnv(ContainerBuilder $builder): void
    {
        $builder->setParameter('twig_debug', ($_ENV['TWIG_DEBUG'] ?? 'false') === 'true');
    }

    private static function setLocale(ContainerBuilder $builder): void
    {
        $locale = get_locale() ?? 'en_US';
        $builder->setParameter('locale_full', $locale);
        $builder->setParameter('locale', explode('_', $locale)[0]);
    }
}
