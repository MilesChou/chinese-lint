<?php

namespace App;

interface Marker
{
    public function wrapSource(string $str): string;

    public function wrapCorrect(string $str): string;
}
