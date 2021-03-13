<?php

declare (strict_types = 1);

namespace App\Middleware;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('test', 'default');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
        // Headers 可以根据实际情况进行改写。
            ->withHeader('Access-Control-Allow-Headers', '*');
        Context::set(ResponseInterface::class, $response);
        // $this->logInfo('[CorsMiddleware] -> ' . $request->getMethod());

        if ($request->getMethod() == 'OPTIONS') {
            return $response;
        }

        return $handler->handle($request);
    }

    public function logInfo($msg = 'Test')
    {
        $test = [1, 2, 3];
        $this->logger->info($msg, $test);
    }
}
