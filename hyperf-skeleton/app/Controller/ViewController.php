<?php

declare (strict_types = 1);

namespace App\Controller;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Logger\LoggerFactory;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;

/**
 * @AutoController
 */
class ViewController extends AbstractController
{
    protected $class = 'ViewController';
    protected $redis;

    public function __construct(LoggerFactory $loggerFactory, ContainerInterface $container)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get($this->class, 'default');
        $this->redis = $container->get(\Redis::class);
    }

    public function index(RenderInterface $render)
    {
        $customers = [];
        if ($this->redis->exists('customers')) {
            // redis
            try {
                $this->redis->expire('customers', 60000);
                $redisGet = $this->redis->get('customers');
                $customersList = json_decode($redisGet);
                foreach ($customersList as $id => $agentId) {
                    $customers[$id] = $agentId;
                }
                $this->logInfo('[redis exist] -> ' . $redisGet);
            } catch (\Throwable $exception) {
                $this->logInfo("[db exception] -> " . $exception);
            }
        } else {
            // db
            try {
                $customersList = Db::table('customers')->where('switch', 1)->pluck('agentId', 'id');
                foreach ($customersList as $id => $agentId) {
                    $customers[$id] = $agentId;
                }
                $this->logInfo('[db select] -> ', $customers);

                $this->redis->set('customers', json_encode($customers), 60000);
                $redisGet = $this->redis->get('customers');
                $this->logInfo('[redis not exist] -> ' . $redisGet);
            } catch (\Throwable $exception) {
                $this->logInfo("[db exception] -> " . $exception);
            }
        }

        $result = array(
            'name' => '',
            'customers' => $customers,
        );
        return $render->render('index', $result);
    }

    public function logInfo($data = '', $dataArray = [])
    {
        $this->logger->info($data, $dataArray);
    }
}
