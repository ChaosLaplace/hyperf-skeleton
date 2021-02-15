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
namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class IndexController extends AbstractController
{
    // 在参数上通过定义 RequestInterface 和 ResponseInterface 来获取相关对象，对象会被依赖注入容器自动注入
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $target = $request->input('target', 'World');
        return [
            'method' => $request->all(),
            'message' => "Hello {$target}.",
        ];
    }

    // public function index()
    // {
    //     $user = $this->request->input('user', 'Hyperf');
    //     $method = $this->request->getMethod();

    //     return [
    //         'method' => $method,
    //         'message' => "Hello {$user}.",
    //     ];
    // }

    public function testGet()
    {
        return [
            'method' => "get",
            'message' => "Test",
        ];
    }

    public function testPost()
    {
        return [
            'method' => "post",
            'message' => "Test",
        ];
    }
}
