<?php

namespace App\ValueObject;

use App\Fixer;
use App\Markers\TerminalMarker;
use App\Rule;
use RuntimeException;

readonly class LintResult
{
    public function __construct(
        public string $file,
        public int $line,
        public string $source,
        public Rule $rule,
    ) {
    }

    public function sourceWithMark(): string
    {
        return $this->rule->markLint($this->source, new TerminalMarker());
    }

    public function correctWithMark(): string
    {
        if (!$this->rule instanceof Fixer) {
            throw new RuntimeException('Cannot auto fix');
        }

        return $this->rule->markFixed($this->source, new TerminalMarker());
    }
}
