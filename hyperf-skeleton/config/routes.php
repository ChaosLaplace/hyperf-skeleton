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
use App\Middleware\CorsMiddleware;
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

Router::get('/favicon.ico', function () {
    return '';
});
// 首頁頁面
Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\ViewController@index');
// 資料處理
Router::AddGroup('/Log', function () {
    // 撈資料
    Router::get('/logGet', 'App\Controller\LogController@logGet');
    Router::get('/logTestGet', 'App\Controller\LogController@logTestGet');
    // 清資料
    Router::get('/logDelete', 'App\Controller\LogController@logDelete');
});
// 測試
Router::AddGroup('/test', function () {
    // 登入
    Router::post('/login', 'App\Controller\LoginController@login');
    // 切換 URL
    Router::get('/urlChange', 'App\Controller\UrlController@urlChange');

    // 支付
    Router::post('/payRequest', 'App\Controller\PayController@payRequest');
    Router::addRoute(['GET', 'POST'], '/payNotify', 'App\Controller\PayController@payNotify');
    // 代付
    Router::post('/repayRequest', 'App\Controller\RePayController@repayRequest');
    Router::addRoute(['GET', 'POST'], '/repayNotify', 'App\Controller\RePayController@repayNotify');
});
