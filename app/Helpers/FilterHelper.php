<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FilterHelper
{
    static function parseFilters(Request $request): array
    {
        $filters = $request->validate([
            'filters.champion_id' => 'nullable|integer',
            'filters.queue_id' => 'nullable|integer',
            'filters.start_date' => 'nullable|string',
            'filters.end_date' => 'nullable|string',
            'filters.should_filter_encounters' => 'nullable|boolean',
        ]);
        if (Arr::has($filters, 'filters')){
            $filters = $filters['filters'];
        }
        $filters_cpy = $filters;
        if (Arr::has($filters_cpy, 'start_time')) {
            $filters_cpy['start_time'] = explode('T', $filters_cpy['start_time'])[0];
        }
        if (Arr::has($filters_cpy, 'end_time')) {
            $filters_cpy['end_time'] = explode('T', $filters_cpy['end_time'])[0];
        }
        if (Arr::has($filters_cpy, 'should_filter_encounters')) {
            $filters['should_filter_encounters'] = $filters_cpy['should_filter_encounters'] = boolval($filters_cpy['should_filter_encounters']);
        }
        return [$filters, $filters_cpy];
    }

}
