<?php

namespace App\Markers;

use App\Marker;

class TerminalMarker implements Marker
{
    public function wrapSource(string $str): string
    {
        return "\033[0;31m$str\033[m";
    }

    public function wrapCorrect(string $str): string
    {
        return "\033[0;32m$str\033[m";
    }
}
