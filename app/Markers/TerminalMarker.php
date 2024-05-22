<?php

namespace App\Markers;

use App\Marker;

class TerminalMarker implements Marker
{
    public function wrapSource(string $str): string
    {
        return "<bg=red>$str</>";
    }

    public function wrapCorrect(string $str): string
    {
        return "<bg=green>$str</>";
    }
}
