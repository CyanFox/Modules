<?php

namespace Modules\Core\Services;

use InvalidArgumentException;

class SpotlightService
{
    protected array $registeredModels = [];

    protected array $manualItems = [];

    protected array $staticItems = [];

    public function registerModel(string $modelClass): void
    {
        if (! in_array($modelClass, $this->registeredModels)) {
            $this->registeredModels[] = $modelClass;
        }
    }

    public function addItem(array $item): void
    {
        if (! isset($item['title']) || ! isset($item['url'])) {
            throw new InvalidArgumentException('Spotlight items must have at least a title and URL');
        }

        $item['description'] = $item['description'] ?? '';
        $item['icon'] = $item['icon'] ?? 'link';
        $item['id'] = $item['id'] ?? 'manual-'.count($this->manualItems);
        $item['model_type'] = $item['model_type'] ?? 'manual';
        $item['module'] = $item['module'] ?? null;
        $item['permissions'] = $item['permissions'] ?? null;

        if (isset($item['module']) && is_string($item['module']) && $this->isTranslationKey($item['module'])) {
            $item['raw_module'] = $item['module'];
            $item['module'] = __($item['module']);
        }

        if (is_string($item['title']) && $this->isTranslationKey($item['title'])) {
            $item['raw_title'] = $item['title'];
            $item['title'] = __($item['title']);
        }

        if (isset($item['description']) && is_string($item['description']) && $this->isTranslationKey($item['description'])) {
            $item['raw_description'] = $item['description'];
            $item['description'] = __($item['description']);
        }

        $this->manualItems[] = $item;
    }

    protected function isTranslationKey(string $text): bool
    {
        return str_contains($text, '::') ||
            preg_match('/^[a-z0-9_]+\.[a-z0-9_.]+$/i', $text);
    }

    public function addStaticItem(array $item): void
    {
        if (! isset($item['title']) || ! isset($item['url'])) {
            throw new InvalidArgumentException('Spotlight items must have at least a title and URL');
        }

        $item['icon'] = $item['icon'] ?? 'link';
        $item['id'] = $item['id'] ?? 'static-'.count($this->staticItems);
        $item['permissions'] = $item['permissions'] ?? null;
        $item['module'] = $item['module'] ?? null;

        if (isset($item['module']) && is_string($item['module']) && $this->isTranslationKey($item['module'])) {
            $item['raw_module'] = $item['module'];
            $item['module'] = __($item['module']);
        }

        if (is_string($item['title']) && $this->isTranslationKey($item['title'])) {
            $item['raw_title'] = $item['title'];
            $item['title'] = __($item['title']);
        }

        if (isset($item['description']) && is_string($item['description']) && $this->isTranslationKey($item['description'])) {
            $item['raw_description'] = $item['description'];
            $item['description'] = __($item['description']);
        }

        $this->staticItems[] = $item;
    }

    public function getRegisteredModels(): array
    {
        return $this->registeredModels;
    }

    public function search(string $term): array
    {
        if (empty($term)) {
            return [];
        }

        $results = [];
        $searchTerm = mb_strtolower($term);

        foreach ($this->registeredModels as $modelClass) {
            $model = new $modelClass;
            $modelResults = $modelClass::spotlightSearch($term)
                ->limit(settings('internal.spotlight.results_limit', config('settings.spotlight_result_limit', 10)))
                ->get()
                ->filter(function ($item) {
                    return $item->userCanViewInSpotlight();
                })
                ->map(function ($item) {
                    return $item->toSpotlightResult();
                });

            $results = array_merge($results, $modelResults->toArray());
        }

        $searchableItems = array_merge(
            $this->getManualItems(),
            $this->getStaticItems(),
        );

        foreach ($searchableItems as $item) {
            $matchFound = false;

            if (str_contains(mb_strtolower($item['title']), $searchTerm)) {
                $matchFound = true;
            }

            if (! empty($item['description']) && str_contains(mb_strtolower($item['description']), $searchTerm)) {
                $matchFound = true;
            }

            if (! empty($item['module']) && str_contains(mb_strtolower($item['module']), $searchTerm)) {
                $matchFound = true;
            }

            if ($matchFound && $this->userCanViewItem($item)) {
                $results[] = $item;
            }
        }

        usort($results, function ($a, $b) use ($searchTerm) {
            $aTitle = mb_strtolower($a['title']);
            $bTitle = mb_strtolower($b['title']);

            if ($aTitle === $searchTerm && $bTitle !== $searchTerm) {
                return -1;
            }
            if ($bTitle === $searchTerm && $aTitle !== $searchTerm) {
                return 1;
            }

            if (str_starts_with($aTitle, $searchTerm) && ! str_starts_with($bTitle, $searchTerm)) {
                return -1;
            }
            if (str_starts_with($bTitle, $searchTerm) && ! str_starts_with($aTitle, $searchTerm)) {
                return 1;
            }

            $aModule = mb_strtolower($a['module'] ?? '');
            $bModule = mb_strtolower($b['module'] ?? '');
            if ($aModule === $searchTerm && $bModule !== $searchTerm) {
                return -1;
            }
            if ($bModule === $searchTerm && $aModule !== $searchTerm) {
                return 1;
            }

            return strcmp($aTitle, $bTitle);
        });

        return array_slice($results, 0, 10);
    }

    public function getManualItems(): array
    {
        $items = $this->manualItems;

        foreach ($items as &$item) {
            $this->refreshTranslations($item);
        }

        return $items;
    }

    protected function refreshTranslations(array &$item): void
    {
        if (isset($item['raw_module'])) {
            $item['module'] = __($item['raw_module']);
        }

        if (isset($item['raw_title'])) {
            $item['title'] = __($item['raw_title']);
        }

        if (isset($item['raw_description'])) {
            $item['description'] = __($item['raw_description']);
        }
    }

    public function getStaticItems(): array
    {
        $items = array_filter($this->staticItems, [$this, 'userCanViewItem']);

        foreach ($items as &$item) {
            $this->refreshTranslations($item);
        }

        return $items;
    }

    protected function userCanViewItem(array $item): bool
    {
        if (empty($item['permissions'])) {
            return true;
        }

        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if (is_array($item['permissions'])) {
            foreach ($item['permissions'] as $permission) {
                if ($user->can($permission)) {
                    return true;
                }
            }
        } else {
            return $user->can($item['permissions']);
        }

        return false;
    }
}
