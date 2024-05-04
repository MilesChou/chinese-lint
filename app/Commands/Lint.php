<?php

namespace App\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Lint extends Command
{
    protected $signature = 'lint {paths?* : Path will to lint} {--config=tclint.example.yaml : Config file path, YAML format}';

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
            ->reduce(function (array $carry, SplFileInfo $file) use ($config) {
                $lint = $this->lint($file, $config);

                if ($lint->isNotEmpty()) {
                    $carry[$file->getRelativePathname()] = $lint;
                }

                return $carry;
            }, []);

        if (empty($result)) {
            $this->line('Pass');

            return self::SUCCESS;
        }

        Collection::make($result)
            ->each(
                fn (Collection $item, $file) => $item->each(function (array $typicalError) use ($file) {
                    $this->line("檔案 $file 裡發現有問題的詞彙：「{$typicalError['error']}」。可以考慮用「{$typicalError['correct']}」");
                }),
            );

        return self::FAILURE;
    }

    private function lint(SplFileInfo $file, $config): Collection
    {
        $content = $file->getContents();

        return Collection::make($config['typical_errors'])
            ->filter(fn (array $typicalError) => str_contains($content, $typicalError['error']))
            ->values();
    }

    private function parseConfig(): ?array
    {
        $file = $this->option('config');

        if (File::missing($file)) {
            return Config::DEFAULT_CONFIG;
        }

        return Yaml::parseFile($file);
    }
}
