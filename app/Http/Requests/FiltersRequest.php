<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltersRequest extends FormRequest
{
    public static function rules(): array
    {
        return [
            'filters.champion_id' => 'nullable|integer',
            'filters.queue_id' => 'nullable|integer',
            'filters.start_date' => 'nullable|string',
            'filters.end_date' => 'nullable|string',
            'filters.should_filter_encounters' => 'nullable|boolean',
        ];
    }

    public function passedValidation()
    {
        if ($this->has('filters.start_date')) {
            $this->replace([
                'filters.start_date' => explode('T', $this->input('filters.start_date'))[0],
            ]);
        }
        if ($this->has('filters.end_date')) {
            $this->replace([
                'filters.end_date' => explode('T', $this->input('filters.end_date'))[0],
            ]);
        }
        if ($this->has('filters.should_filter_encounters')) {
            $this->replace([
                'filters.should_filter_encounters' => boolval($this->input('filters.should_filter_encounters')),
            ]);
        }
    }
}
