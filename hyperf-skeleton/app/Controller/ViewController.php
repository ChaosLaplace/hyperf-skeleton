<?php

declare (strict_types = 1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Logger\LoggerFactory;
use Hyperf\View\RenderInterface;
// redis
use Psr\Container\ContainerInterface;
use Hyperf\RateLimit\Storage\RedisStorage;
// db
use Hyperf\DbConnection\Db;

/**
 * @AutoController
 */
class ViewController extends AbstractController
{
    protected $redis;

    public function __construct(LoggerFactory $loggerFactory, ContainerInterface $container)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('test', 'default');
        $this->redis = $container->get(\Redis::class);
    }

    public function index(RenderInterface $render)
    {
        $customers = [];
        $this->logInfo('[customers] 1 -> ' . json_encode($customers));
        // redis
        // try {
        //     $this->redis->set('name', 12345, 60);
        //     $user .= $this->redis->get('name');
        //     $customers[] = $user;
        // } catch (\Throwable $exception) {
        //     $this->logInfo("[redis exception] -> " . $exception);
        // }

        // db
        try {
            $customersList = Db::table('customers')->where('switch', 1)->pluck('agentId', 'id');
            foreach ($customersList as $id => $agentId) {
                $customers[$id] = $agentId;
            }
        } catch (\Throwable $exception) {
            $this->logInfo("[db exception] -> " . $exception);
        }
        $this->logInfo('[customers] 2 -> ' . json_encode($customers));

        $result = array(
            'name' => '',
            'customers' => $customers,
        );
        return $render->render('index', $result);
    }

    public function logInfo($msg = 'Test')
    {
        $test = [1, 2, 3];
        $this->logger->info($msg, $test);
    }
}
