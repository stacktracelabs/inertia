<?php

namespace StackTrace\Inertia;

use Illuminate\Support\Facades\App;
use function array_merge;
use function file_exists;
use function file_get_contents;
use function json_decode;
use function lang_path;

class Localization
{
    protected array $translations = [];

    public function getTranslations(): array
    {
        if (empty($this->translations)) {
            $this->translations = array_merge(
                $this->loadTranslationsForLocale(App::getFallbackLocale()),
                $this->loadTranslationsForLocale(App::getLocale())
            );
        }

        return $this->translations;
    }

    protected function loadTranslationsForLocale(string $locale)
    {
        $localeFile = lang_path($locale . ".json");

        if (file_exists($localeFile)) {
            return json_decode(file_get_contents($localeFile), true);
        }

        return [];
    }
}
