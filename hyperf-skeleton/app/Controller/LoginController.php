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

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;

class LoginController
{
    private $request;

    public function __construct(RequestInterface $requestInterface)
    {
        $this->request = $requestInterface;
    }
    // 登入
    public function login()
    {
        $data = $this->request->all();
        // db
        try {
            $login = Db::table('login')->where([
                ['account', '=', $data['account']],
                ['password', '=', $data['password']],
            ])->get();

            if (count($login) > 0) {
                return 'true';
            }
            return 'false';
        } catch (\Throwable $exception) {
            return ['[db exception]', $exception];
        }
    }
}
