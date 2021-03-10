<?php

declare (strict_types = 1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\View\Engine\BladeEngine;
use Hyperf\View\Mode;

return [
    // 将 engine 参数改为您的自定义模板引擎类
     'engine' => BladeEngine::class,
    // 不填写则默认为 Task 模式，推荐使用 Task 模式
     'mode' => Mode::TASK,
    'config' => [
        'view_path' => BASE_PATH . '/storage/view/',
        'cache_path' => BASE_PATH . '/storage/view_temp/',
    ],
];
