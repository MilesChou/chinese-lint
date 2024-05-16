<?php

namespace App\Configuration;

use App\Rules\TypicalErrorFixer;
use InvalidArgumentException;

class Config
{
    private array $rules;

    public function __construct(array $config = [])
    {
        $this->rules = array_merge(
            [],
            $this->parseTypicalErrors($config['typical_errors'] ?? []),
            $this->parseRules($config['rules'] ?? []),
        );
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

    public function rules(): array
    {
        return $this->rules;
    }

    private function parseRules(array $rules): array
    {
        return array_map(fn($rule) => new $rule, $rules);
    }
}
