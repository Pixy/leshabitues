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
        // Make sure we do not compute those attributes, otherwise the hash will never be the same
        $attributesToFilter = ['id', 'hash', 'created_at', 'updated_at'];
        foreach ($attributesToFilter as $attr) {
            if (isset($attributes[$attr])) {
                unset($attributes[$attr]);
            }
        }

        // Ensure all values are strings, to avoid issue while comparing
        array_walk_recursive($attributes, function (&$value) {
            $value = (string) $value;
        });

        $hashString = json_encode($attributes);
        return strlen($hashString) . hash('sha256', $hashString);
    }
}