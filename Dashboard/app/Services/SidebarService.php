<?php

namespace Modules\Dashboard\Services;

use Illuminate\Support\Collection;

class SidebarService
{
    protected array $sidebarItems = [];

    public function add(string $label, string $icon, string $route = 'dashboard', ?string $link = null, bool $isExternal = false)
    {
        $this->sidebarItems[] = [
            'label' => $label,
            'icon' => $icon,
            'url' => $link,
            'route' => $route,
            'external' => $isExternal,
        ];
    }

    public function get(?string $label = null)
    {
        $sidebarItems = $this->sidebarItems;
        if ($label !== null) {
            if (! isset($this->menu[$label])) {
                return null;
            }
            $sidebarItems = $this->sidebarItems[$label];
        }

        return new Collection($sidebarItems);
    }

    public function getAll(): array
    {
        return $this->sidebarItems;
    }
}
