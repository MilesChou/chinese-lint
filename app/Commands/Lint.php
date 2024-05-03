<?php

namespace App\Commands;

use Illuminate\Support\Collection;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Lint extends Command
{
    protected $signature = 'lint {paths?*}';

    protected $description = 'Lint Traditional Chinese words';

    public function handle(): int
    {
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
            ->reduce(function (array $carry, SplFileInfo $file) {
                $lint = $this->lint($file);

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
                fn (Collection $item, $key) => $item->each(
                    fn ($correct, $wrong) => $this->line("檔案 $key 裡發現有問題的詞彙：「{$wrong}」。可以考慮用「{$correct}」"),
                ),
            );

        return self::FAILURE;
    }

    private function lint(SplFileInfo $file): Collection
    {
        static $traditionalChineseWords = [
            '網絡' => '網路',
            '軟件' => '軟體',
            '線程' => '執行緒',
            '調試' => '呼叫',
            '集成' => '整合',
            '中間件' => '中介層',
            '插件' => '外掛',
            '端口' => '埠',
            '調用' => '呼叫',
            '信息' => '訊息',
            '交互' => '互動',
            '組件' => '元件',
            '代碼' => '程式碼',
            '界面' => '介面',
            '運維' => '維運',
            '部屬' => '部署',
            '發佈' => '發布',
        ];

        $content = $file->getContents();

        return Collection::make($traditionalChineseWords)
            ->filter(fn (string $_, $word) => str_contains($content, $word));
    }
}
