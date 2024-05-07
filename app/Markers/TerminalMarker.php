<?php

namespace App\Markers;

use App\Marker;

class TerminalMarker implements Marker
{
    public function wrapSource(string $str): string
    {
        return "<bg=#BB0000>$str</>";
    }

    public function wrapCorrect(string $str): string
    {
        return "<bg=#007700>$str</>";
    }
}
