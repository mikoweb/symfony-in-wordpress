<?php

namespace App\Module\Core\Application\Translator;

use App\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslatorLoader
{
    private bool $loaded = false;

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function load(): void
    {
        if (!$this->loaded) {
            $this->translator->addLoader('yaml', new YamlFileLoader());
            $this->translator->addLoader('xlf', new XliffFileLoader());
            $finder = new Finder();
            $finder->depth('== 0')->in(Path::getTranslationsPath())->name('messages.*.yaml');
            $this->loadFromFinder($finder, 'messages');
            $this->loadValidatorResourcesFromVendor();

            $this->loaded = true;
        }
    }

    private function loadValidatorResourcesFromVendor(): void
    {
        $paths = [
            Path::getVendorPath('symfony/validator/Resources/translations'),
            Path::getVendorPath('symfony/form/Resources/translations'),
        ];

        $finder = new Finder();
        $finder->depth('== 0')->in($paths)->name('validators.*.xlf');

        $this->loadFromFinder($finder, 'validators', 'xlf');
    }

    private function loadFromFinder(Finder $finder, string $domain = null, string $extension = 'yaml'): void
    {
        foreach ($finder as $file) {
            $locale = $this->getLocaleFromFilename($file->getRelativePathname(), $domain, $extension);

            if (!is_null($locale)) {
                $this->translator->addResource($extension, $file->getPathname(), $locale, $domain);
            }
        }
    }

    private function getLocaleFromFilename(
        string $fileName,
        string $domain = 'messages',
        string $extension = 'yaml'
    ): ?string
    {
        preg_match("/$domain\.(.+)\.$extension/", $fileName, $matches);

        return count($matches) > 0 ? $matches[1] : null;
    }
}
