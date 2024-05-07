<?php

namespace App;

/**
 * Check rule
 */
interface Rule
{
    /**
     * @param string $line Just one line
     * @return bool
     */
    public function lint(string $line): bool;

    /**
     * @param string $line Just one line
     * @param Marker $marker
     * @return string
     */
    public function markLint(string $line, Marker $marker): string;
}
