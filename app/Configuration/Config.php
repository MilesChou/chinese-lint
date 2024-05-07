<?php

namespace App\Configuration;

use App\Rules\TypicalErrorFixer;
use InvalidArgumentException;

class Config
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config['typical_errors'] = $this->parseTypicalErrors($config['typical_errors'] ?? []);
    }

    private function parseTypicalErrors(array $config): array
    {
        return array_map(function ($rule) {
            if (!isset($rule['error'], $rule['correct'])) {
                throw new InvalidArgumentException('Rule "error" or "correct is missing for "typical_errors" key');
            }

            return new TypicalErrorFixer($rule['error'], $rule['correct']);
        }, $config);
    }

    public function toArray(): array
    {
        return $this->config;
    }
}
