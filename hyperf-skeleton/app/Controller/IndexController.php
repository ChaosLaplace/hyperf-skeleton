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

use Hyperf\Logger\LoggerFactory;

class IndexController extends AbstractController
{
    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('test', 'default');
    }

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function testGet()
    {
        return [
            'method' => 'GET',
            'message' => 'Test',
        ];
    }

    public function testPost()
    {
        return [
            'method' => 'POST',
            'message' => 'Test',
        ];
    }

    public function logInfo($msg = 'Test')
    {
        $test = [1, 2, 3];
        $this->logger->info($msg, $test);
    }
}
