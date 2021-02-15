<?php
declare (strict_types = 1);
namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * @Controller()
 */
class UserController
{
    public function info(int $id)
    {
        $user = User::find($id);
        return $user->toArray();
    }
    // Hyperf 会自动为此方法生成一个 /user/index 的路由，允许通过 GET 或 POST 方式请求
    /**
     * @RequestMapping(path="index", methods="get,post")
     */
    public function index(RequestInterface $request)
    {
        // 存在则返回，不存在则返回默认值 0
        $id = $request->route('id', 0);
    }
}
