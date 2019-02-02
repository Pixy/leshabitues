<?php
/**
 * Helpers file
 */


if (!function_exists('computeHash')) {
    /**
     * Generate hash for current object based on all other attributes
     *
     * @param array $attributes
     * @return string
     */
    function computeHash($attributes = [])
    {
        // Make sure there isn't already a hash in the array
        if (isset($attributes['hash'])) {
            unset($attributes['hash']);
        }

        // Ensure all values are strings, to avoid issue while comparing
        array_walk_recursive($attributes, function (&$value) {
            $value = (string) $value;
        });

        $hashString = json_encode($attributes);
        $hash = strlen($hashString) . hash('sha256', $hashString);

        return $hash;
    }
}