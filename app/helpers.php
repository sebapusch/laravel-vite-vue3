<?php

use App\Service\LanguageService\LanguageServiceInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;

/**
 * @param string|array|null $translationFiles
 * @return HtmlString
 */
function load_assets(string|array|null $translationFiles = null): HtmlString
{
    $languageService = App::make(LanguageServiceInterface::class);
    $languageScript = '';
    $isLocal = app()->environment('local');

    try {
        $translations = $languageService->jsonTranslations($translationFiles);
        $languageScript = <<<HTML
                <script>window.__trans = $translations</script>
            HTML;
    } catch (\Exception $e) {
        if ($isLocal) {
            $exceptionMessage = $e->getMessage();
            $languageScript = <<<HTML
                <script>console.error("$exceptionMessage")</script>
            HTML;
        }
    }

    if ($isLocal) {
        return new HtmlString(<<<HTML
            $languageScript
            <script type="module" src="http://localhost:3000/@vite/client"></script>
            <script type="module" src="http://localhost:3000/resources/js/app.js"></script>
        HTML);
    }

    $manifest = json_decode(file_get_contents(
        public_path('build/manifest.json')
    ), true);

    return new HtmlString(<<<HTML
        $languageScript
        <script type="module" src="/build/{$manifest['resources/js/app.js']['file']}"></script>
        <link rel="stylesheet" href="/build/{$manifest['resources/js/app.js']['css'][0]}">
    HTML);
}
