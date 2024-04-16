<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BaseScope
{
    // public function scopeStatus(Builder $query, $status): Builder
    // {
    //     return $query->where($this->getTable() . '.status', $status);
    // }

    public function scopeModelSort(Builder $query, $sortParamsString)
    {
        $this->detachSortParams($sortParamsString)->each(function ($item) use ($query) {
            $query = $query->orderBy($item['sortColumn'], $item['sortType'] ?: 'ASC');
        });
        return $query;
    }

    private function detachSortParams($sortParamsString)
    {
        $sortParams = explode(',', $sortParamsString);
        return collect($sortParams)->map(function ($sortParam) {
            [$sortColumn, $sortType] = explode('.', $sortParam . '.');
            return [
                'sortColumn' => $sortColumn,
                'sortType' => $sortType,
            ];
        });
    }
}
