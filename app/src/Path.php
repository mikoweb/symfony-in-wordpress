<?php

namespace App;

use function Symfony\Component\String\u;

final class Path
{
    public static function getAppPath(?string $path = null): string
    {
        return self::concatPath(dirname(__DIR__), $path);
    }

    public static function getSrcPath(?string $path = null): string
    {
        return self::concatPath(__DIR__, $path);
    }

    public static function getConfigPath(?string $path = null): string
    {
        return self::getAppPath(self::concatPath('config', $path));
    }

    public static function getTranslationsPath(?string $path = null): string
    {
        return self::getAppPath(self::concatPath('translations', $path));
    }

    public static function getTemplatesPath(?string $path = null): string
    {
        return self::getAppPath(self::concatPath('templates', $path));
    }

    public static function getCachePath(?string $path = null): string
    {
        return self::getAppPath(self::concatPath('var/cache', $path));
    }

    public static function getVendorPath(?string $path = null): string
    {
        return self::getAppPath(self::concatPath('vendor', $path));
    }

    public static function concatPath(string $basePath, ?string $path): string
    {
        if (empty($path)) {
            return $basePath;
        }

        if (u($path)->startsWith('/')) {
            $path = u($path)->slice(1)->toString();
        }

        return "$basePath/$path";
    }
}
