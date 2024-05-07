<?php

namespace App;

/**
 * Fix rule
 */
interface Fixer extends Rule
{
    /**
     * Input source line and output fixed lint
     *
     * @param string $line Just one line
     * @return string
     */
    public function fix(string $line): string;

    /**
     * @param string $line Just one line
     * @param Marker $marker
     * @return string
     */
    public function markFixed(string $line, Marker $marker): string;
}
