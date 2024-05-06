<?php

namespace App\Commands;

use App\ValueObject\LintResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Lint extends Command
{
    protected $signature = 'lint {paths?* : Path will to lint} {--config=tclint.yaml : Config file path, YAML format}';

    protected $description = 'Lint Traditional Chinese words';

    public function handle(): int
    {
        $config = $this->parseConfig();

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

        $result->each(function (Enumerable $item) {
            $item->each(function (LintResult $lintResult) {
                $file = "$lintResult->file:$lintResult->line";
                $error = $lintResult->rule;
                $correct = $lintResult->correct;
                $this->line("檔案 $file 裡發現有問題的詞彙：「{$error}」。可以考慮用「{$correct}」");
                $this->line("- {$lintResult->sourceWithMark()}");
                $this->line("+ {$lintResult->correctWithMark()}");
            });
        });

        return self::FAILURE;
    }

    private function parseConfig(): ?array
    {
        $file = $this->option('config');

        if (File::missing($file)) {
            return Config::DEFAULT_CONFIG;
        }

        return Yaml::parseFile($file);
    }

    private function lint(SplFileInfo $file, $config): Enumerable
    {
        $content = File::lines($file->getRealPath());

        return Collection::make($config['typical_errors'])
            ->map(fn(array $typicalError) => $this->lintRule($content, $typicalError, $file->getRelativePathname()))
            ->reject(fn(Enumerable $collection) => $collection->isEmpty())
            ->values();
    }

    private function lintRule(Enumerable $content, array $typicalError, string $file): Enumerable
    {
        return $content->filter(fn(string $value) => str_contains($value, $typicalError['error']))
            ->map(function (string $value, int $key) use ($typicalError, $file) {
                return new LintResult(
                    $file,
                    $key + 1,
                    $value,
                    $typicalError['error'],
                    $typicalError['correct'],
                );
            })
            ->values();
    }
}
