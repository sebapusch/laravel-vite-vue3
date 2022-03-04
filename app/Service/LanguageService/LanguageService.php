<?php

namespace App\Service\LanguageService;

use App\Exceptions\LanguageService\InvalidLocaleException;
use App\Exceptions\LanguageService\TranslationFileNotFoundException;

class LanguageService implements LanguageServiceInterface
{
    /**
     * @var string
     */
    protected string $locale;

    /**
     * @var string
     */
    protected string $fallbackLocale;

    /**
     * @var array
     */
    protected array $availableLocales;

    public function __construct()
    {
        $this->locale = app()->getLocale();
        $this->fallbackLocale = config('app.fallback_locale', 'en');
        $this->availableLocales = config('config.available_locales', ['en']);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return array
     */
    public function getAvailableLocales(): array
    {
        return $this->availableLocales;
    }

    /**
     * @param string $locale
     *
     * @throws InvalidLocaleException
     */
    public function setLocale(string $locale): void
    {
        if(false === in_array($locale, $this->availableLocales)) {
            throw new InvalidLocaleException("Invalid locale ${locale}");
        }

        $this->locale = $locale;
        config(['app.locale' => $locale]);
    }

    /**
     * @param string|array|null $filename
     * @param bool $includeCommon
     * @return string
     * @throws TranslationFileNotFoundException
     */
    public function jsonTranslations(string|array|null $filename = null, bool $includeCommon = true): string
    {
        $files = match (gettype($filename)) {
            'string' => [$filename],
            'array' => $filename,
            default => [],
        };

        if ($includeCommon) {
            $files[] = 'common';
        }

        $translations = ['locale' => [], 'fallback_locale' => []];
        foreach ($files as $file) {
            $translationFilePaths = $this->getFilePaths($file);
            if (false === $translationFilePaths) {
                throw new TranslationFileNotFoundException("Unable to read translation file '{$file}'");
            }

            foreach (['locale', 'fallback_locale'] as $key) {
                if (array_key_exists($key, $translationFilePaths)) {
                    $translationContent = [$file => require_once $translationFilePaths[$key]];
                    $translations[$key] =  array_merge($translations[$key], $translationContent);
                }
            }
        }

        return json_encode($translations);
    }


    /**
     * @param string $filename
     * @return array|bool
     */
    private function getFilePaths(string $filename): array|bool
    {
        $paths = [];
        $locales = [
            'locale' => $this->locale,
        ];

        if ($this->locale !== $this->fallbackLocale) {
            $locales['fallback_locale'] = $this->fallbackLocale;
        }

        foreach ($locales as $locale => $lang) {
            $path = resource_path() . "/lang/{$lang}/{$filename}.php";
            if(file_exists($path)) {
                $paths[$locale] = $path;
            }
        }

        if (empty($paths)) {
            return false;
        }

        return $paths;
    }
}
