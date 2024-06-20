<?php

namespace App\Configuration;

use App\Rules;

readonly class DefaultConfig
{
    public const DEFAULT_CONFIG = [
        'typical_errors' => [
            [
                'error' => '網絡',
                'correct' => '網路',
            ],
            [
                'error' => '軟件',
                'correct' => '軟體',
            ],
            [
                'error' => '線程',
                'correct' => '執行緒',
            ],
            [
                'error' => '調試',
                'correct' => '呼叫',
            ],
            [
                'error' => '集成',
                'correct' => '整合',
            ],
            [
                'error' => '中間件',
                'correct' => '中介層',
            ],
            [
                'error' => '插件',
                'correct' => '外掛',
            ],
            [
                'error' => '端口',
                'correct' => '埠',
            ],
            [
                'error' => '調用',
                'correct' => '呼叫',
            ],
            [
                'error' => '信息',
                'correct' => '訊息',
            ],
            [
                'error' => '交互',
                'correct' => '互動',
            ],
            [
                'error' => '組件',
                'correct' => '元件',
            ],
            [
                'error' => '代碼',
                'correct' => '程式碼',
            ],
            [
                'error' => '界面',
                'correct' => '介面',
            ],
            [
                'error' => '運維',
                'correct' => '維運',
            ],
            [
                'error' => '部屬',
                'correct' => '部署',
            ],
            [
                'error' => '發佈',
                'correct' => '發布',
            ],
            [
                'error' => '添加',
                'correct' => '新增',
            ],
            [
                'error' => '創建',
                'correct' => '建立',
            ],
            [
                'error' => '生成',
                'correct' => '產生',
            ],
            [
                'error' => '您',
                'correct' => '你',
            ],
            [
                'error' => '遠程',
                'correct' => '遠端',
            ],
            [
                'error' => '響應',
                'correct' => '回應',
            ],
            [
                'error' => '批量',
                'correct' => '批次',
            ],
            [
                'error' => '創建',
                'correct' => '建立',
            ],
            [
                'error' => '極限編程',
                'correct' => '極限開發',
            ],
            [
                'error' => '建構',
                'correct' => '建置',
            ],
            [
                'error' => '構建',
                'correct' => '建置',
            ],
            [
                'error' => '項目',
                'correct' => '專案',
            ],
        ],
        'rules' => [
            Rules\PunctuationErrorFixer::class,
            Rules\SpaceErrorFixer::class,
        ],
    ];
}
