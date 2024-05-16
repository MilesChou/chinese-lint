<?php

namespace App\Rules;

use App\Fixer;
use App\Marker;

readonly class AroundNumberSpaceFixer implements Fixer
{
    public function __construct(private string $error, private string $correct)
    {
    }

    public function lint(string $line): bool
    {
        // 今天出去買菜花了 5000 元。
        return preg_match('/^\s*$/', $line);
    }

    public function markLint(string $line, Marker $marker): string
    {
        return str_replace($this->error, $marker->wrapSource($this->error), $line);
    }

    public function fix(string $line): string
    {
        return str_replace($this->error, $this->correct, $line);
    }

    public function markFixed(string $line, Marker $marker): string
    {
        return str_replace($this->error, $marker->wrapCorrect($this->correct), $line);
    }
}
