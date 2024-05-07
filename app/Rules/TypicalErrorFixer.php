<?php

namespace App\Rules;

use App\Fixer;
use App\Marker;

readonly class TypicalErrorFixer implements Fixer
{
    public function __construct(private string $error, private string $correct)
    {
    }

    public function lint(string $line): bool
    {
        return str_contains($line, $this->error);
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
