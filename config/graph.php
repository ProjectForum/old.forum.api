<?php

return [
    // 类型注册表
    'types' => [
        'forum' => [
            'query' => \forum\graph\Query::class,
        ]
    ],
    // 入口类型
    'schema' => [
        'forum'
    ],
    // 中间件
    'middleware' => [],
    // 路由前缀
    'routePrefix' => 'api/'
];
