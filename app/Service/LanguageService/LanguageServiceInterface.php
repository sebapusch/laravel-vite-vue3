<?php

namespace App\Service\LanguageService;

interface LanguageServiceInterface
{
    public function getLocale(): string;
    public function getAvailableLocales(): array;
    public function setLocale(string $locale): void;

    public function jsonTranslations(?string $filename = null, bool $includeCommon = true): string;
}
