<?php
declare(strict_types=1);

namespace App\Policy;

class FallbackPolicy
{
    /**
     * Fallback for any authorization check (can* and scope*)
     */
    public function __call(string $name, array $arguments)
    {
        if (str_starts_with(strtolower($name), 'scope')) {
            // Return the query object which is the second argument (or first if passed directly)
            return $arguments[1] ?? ($arguments[0] ?? null);
        }
        return true;
    }
}
