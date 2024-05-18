<?php

namespace App\Rules;

use App\Fixer;
use App\Marker;

readonly class PunctuationErrorFixer implements Fixer
{
    public function __construct()
    {
    }

    public function lint(string $line): bool
    {
        return preg_match($this->getRegex(), $line);
    }

    public function markLint(string $line, Marker $marker): string
    {
        return str_replace($this->findQuotes($line), $this->markQuotes($line, $marker), $line);
    }

    public function fix(string $line): string
    {
        return $this->replaceQuotes($line);
    }

    public function markFixed(string $line, Marker $marker): string
    {
        return str_replace($this->findQuotes($line), $this->markFixedQuotes($line, $marker), $line);
    }

    private function getRegex(): string
    {
        // 取得正則表達式
        return '#([‘’“”\'"〝〞])(?:\p{Han}+|^)([‘’“”\'"〝〞])(?! >}})#u';
    }

    private function findQuotes(string $line): ?string
    {
        return preg_replace_callback($this->getRegex(), function ($matches) {
            return $matches[0];
        }, $line);
    }

    private function markQuotes(string $line, Marker $marker): ?string
    {
        return preg_replace_callback($this->getRegex(), function ($matches) use ($marker) {
            return $marker->wrapSource($matches[0]);
        }, $line);
    }

    private function replaceQuotes(string $line): ?string
    {
        return preg_replace_callback($this->getRegex(), function ($matches) {
            $content = mb_substr($matches[0], 1, -1, 'UTF-8');

            return '「' . $content . '」';
        }, $line);
    }

    private function markFixedQuotes(string $line, Marker $marker): ?string
    {
        return preg_replace_callback($this->getRegex(), function ($matches) use ($marker) {
            $content = mb_substr($matches[0], 1, -1, 'UTF-8');

            return $marker->wrapCorrect('「' . $content . '」');
        }, $line);
    }
}
