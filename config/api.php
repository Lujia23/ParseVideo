<?php

return [
    'api' => [
        // 短视频API
        'douyin.com' => [
            'provider' => \App\Services\ParseShortVideo::class
        ],

        'kuaishou.com' => [
            'provider' => \App\Services\ParseShortVideo::class
        ],

        // B站API
        'bilibili.com' => [
            'provider' => \App\Services\ParseBilibili::class
        ],
        'b23.tv' => [
            'provider' => \App\Services\ParseBilibili::class
        ],
    ]
];
