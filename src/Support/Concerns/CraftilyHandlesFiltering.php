<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Support\Concerns;

use VPremiss\Crafty\Support\Exceptions\CraftyFilteringException;

trait CraftilyHandlesFiltering
{
    public function filterProps(mixed $all, mixed $only = [], mixed $except = []): array
    {
        if (filled($except) && filled($only)) {
            throw new CraftyFilteringException("You shouldn't use both `except` and `only` arguments, only one of them.");
        }

        $allItems = collect($all);

        if (is_associative_array($allItems->toArray())) {
            if (filled($except)) {
                $filteredItems = $allItems->reject(fn ($value, $key) => collect($except)->contains($key));
            } elseif (filled($only)) {
                $filteredItems = $allItems->filter(fn ($value, $key) => collect($only)->contains($key));
            } else {
                $filteredItems = $allItems;
            }
        } else {
            if (filled($except)) {
                $filteredItems = $allItems->reject(fn ($item) => collect($except)->contains($item));
            } elseif (filled($only)) {
                $filteredItems = $allItems->filter(fn ($item) => collect($only)->contains($item));
            } else {
                $filteredItems = $allItems;
            }
        }

        return $filteredItems->values()->all();
    }
}
