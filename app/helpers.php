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

/**
 * Generate a unique token within a specified range or use a given token
 *
 * @param int|null $from The lower bound of the random number range (required if $intoken is not provided)
 * @param int|null $to The upper bound of the random number range (required if $intoken is not provided)
 * @param string $table The name of the table to check for uniqueness
 * @param string $column The column in the table to check for uniqueness
 * @param int|string|null $intoken An optional predefined token
 * @return int|string The generated unique token
 * @throws InvalidArgumentException If range parameters are invalid when no predefined token is given
 * @throws Exception If a unique token cannot be generated
 */
function generate_tokens(int $from = null, int $to = null, string $table, string $column, $intoken = null) {
    // Validate the range only if no predefined token is given
    if ($intoken === null) {
        if ($from === null || $to === null) {
            throw new InvalidArgumentException('Range parameters must be provided when no predefined token is given.');
        }

        if ($from > $to) {
            throw new InvalidArgumentException('The lower bound must be less than or equal to the upper bound.');
        }
    }

    // Attempt to generate or validate a unique token
    $attempts = 0;
    do {
        $token = $intoken ? $intoken : mt_rand($from, $to);
        $exists = \DB::table($table)->where($column, $token)->exists();

        // Add a limit to the number of attempts to avoid potential infinite loop
        if (++$attempts > 1000) {
            throw new Exception('Unable to generate a unique token.');
        }
    } while ($exists);

    return $token;
}



