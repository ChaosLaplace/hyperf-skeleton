<?php

declare (strict_types = 1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

// # 0.把需要引入的包写入composer.json中 写入的话，就不需要composer require了，直接composer update 即可
// composer.json ,"hyperf/view": "~2.0.0","hyperf/task": "~2.0.0"
// composer update
// # 1.安装 视图支持
// composer require hyperf/view
// # 2.安装 task 机制支持
// composer require hyperf/task
// # 3.安装 视图引擎
// composer require duncan3dc/blade

// Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::AddGroup('/test', function () {
    Router::get('/testGet', 'App\Controller\IndexController@testGet');
    Router::post('/testPost', 'App\Controller\IndexController@testPost');
});

Router::AddGroup('/view', function () {
    Router::get('/index', 'App\Controller\UserController@index');
});
