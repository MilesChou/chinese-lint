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

        $result = Collection::make($files)
            ->reduce(function (Collection $carry, SplFileInfo $file) use ($config) {
                $content = file_get_contents($file->getPathname()); // 讀取檔案內容
                // 添加正則表達式來匹配標點符號
                preg_match_all('#([‘’“”\'"〝〞])(?:\p{Han}+|^)([‘’“”\'"〝〞])#u', $content, $matches);

                $lint = $this->lint($file, $config);

                if ($lint->isNotEmpty() || !empty($matches[0])) {
                    $carry->put($file->getRelativePathname(), [
                        'lint' => $lint,
                        'punctuation' => $matches[0] ?? [],
                    ]);
                }

                return $carry;
            }, collect());

        if ($result->isEmpty()) {
            $this->line('Pass');

            return self::SUCCESS;
        }

        $result->each(function (array $item, $file) {
            collect($item['lint'])->each(function (array $typicalError) use ($file) {
                $this->line("檔案 $file 裡發現有問題的詞彙：「{$typicalError['error']}」。可以考慮用「{$typicalError['correct']}」");
            });
            collect($item['punctuation'])->each(function ($punctuation) use ($file) {
                $this->line("檔案 $file 裡發現有問題的標點符號：「{$punctuation}」");
            });

        });

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
