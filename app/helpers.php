<?php

if (!function_exists('filter_empty_values')) {
    /**
     * Filter out empty values from an array.
     *
     * @param array $data
     * @return array
     */
    function filter_empty_values(array $data): array
    {
        return \Illuminate\Support\Arr::where($data, function ($value) {
            return !is_null($value) && $value !== '';
        });
    }
}
