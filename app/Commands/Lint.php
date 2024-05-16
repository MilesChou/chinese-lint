<?php

namespace App\Commands;

use App\Configuration\Config;
use App\Configuration\DefaultConfig;
use App\Rule;
use App\ValueObject\LintResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Lint extends Command
{
    protected $signature = 'lint {paths?* : Path will to lint} {--config=tclint.yaml : Config file path, YAML format}';

    protected $description = 'Lint Traditional Chinese words';

    protected array $fileCache = [];

    public function handle(): int
    {
        $config = new Config($this->parseConfig());

        $paths = $this->argument('paths');

        if (empty($paths)) {
            $paths = ['.'];
        }

        $files = (new Finder())
            ->in($paths)
            ->files()
            ->name('*.md');

        /** @var Collection $result */
        $result = Collection::make($files)
            ->map(fn(SplFileInfo $file) => $this->lint($file, $config))
            ->reject(fn(Enumerable $collection) => $collection->isEmpty())
            ->flatten();

        if (empty($result)) {
            $this->line('Pass');

            return self::SUCCESS;
        }

        $result->each(function (LintResult $lintResult) {
            $file = "$lintResult->file:$lintResult->line";
            $this->line("檔案 $file 裡發現有問題的詞彙可以修正");
            $this->line("- {$lintResult->sourceWithMark()}");
            $this->line("+ {$lintResult->correctWithMark()}");
            $this->newLine();
        });

        return self::FAILURE;
    }

    private function parseConfig(): array
    {
        $file = $this->option('config');

        if (File::missing($file)) {
            return DefaultConfig::DEFAULT_CONFIG;
        }

        $parsed = Yaml::parseFile($file);

        if (empty($parsed)) {
            throw new InvalidArgumentException('Invalid config file');
        }

        return $parsed;
    }

    private function lint(SplFileInfo $file, Config $config): Enumerable
    {
        $content = Collection::make($this->getFileContent($file));

        return Collection::make($config->rules())
            ->map(fn(Rule $typicalError) => $this->lintRule($content, $typicalError, $file->getRelativePathname()))
            ->reject(fn(Enumerable $collection) => $collection->isEmpty())
            ->values();
    }

    private function lintRule(Enumerable $content, Rule $rule, string $file): Enumerable
    {
        return $content->filter(fn(string $value) => $rule->lint($value))
            ->map(fn(string $value, int $key) => new LintResult($file, $key + 1, $value, $rule))
            ->values();
    }

    /**
     * @param SplFileInfo $file
     * @return array
     */
    public function getFileContent(SplFileInfo $file): array
    {
        return $this->fileCache[$file->getRelativePathname()] ??= File::lines($file->getRealPath())->toArray();
    }
}
