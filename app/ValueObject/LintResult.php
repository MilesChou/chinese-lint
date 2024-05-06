<?php

namespace App\ValueObject;

readonly class LintResult
{
    public function __construct(
        public string $file,
        public int $line,
        public string $source,
        public string $rule,
        public string $correct,
    ) {
    }

    public function sourceWithMark(): string
    {
        return str_replace($this->rule, "<bg=#BB0000>$this->rule</>", $this->source);
    }

    public function correctWithMark(): string
    {
        return str_replace($this->rule, "<bg=#007700>$this->correct</>", $this->source);
    }
}
