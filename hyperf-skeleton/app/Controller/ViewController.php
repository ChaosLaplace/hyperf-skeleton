<?php

declare (strict_types = 1);

namespace App\Controller;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\View\RenderInterface;
use Hyperf\Logger\LoggerFactory;

use Hyperf\Utils\ApplicationContext;
// use App\Model\CustomersModel;
/**
 * @AutoController
 */
class ViewController extends AbstractController
{
    private $redis;

    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('test', 'default');
    }

    public function index(RenderInterface $render)
    {
        $customers = NULL;
        $this->logInfo('[customers] 1 -> ' . json_encode($customers));

        $container = ApplicationContext::getContainer();
        // $this->redis = $container->get(\Redis::class);
 
        // $this->redis->set('name', 12345);
        // $user .= $this->redis->get('name');
        
        $customers = $container;

        try {
            // $customers = $this->customersModel->getAll();
            // $customers = Db::table("customers")
            // ->where([
            //     "switch" => 1,
            // ])->limit(1000)
            // ->select("agentId")
            // ->get();
            // $customers = Db::select('SELECT * FROM customers;');
            // $customers = Db::table('customers')->get();
            // $customers = Db::select('SELECT * FROM `customers` WHERE `switch` = ?', [1]);  //  返回array 
            // Db::table('customers')->where('switch', 1)->chunkById(100, function ($customers) {
            //     foreach ($customers as $k => $v) {
            //         $customers[$k] = $v;
            //     }
            // });
        } catch(\Throwable $exception) {
            $this->logInfo($exception);
        }
        $this->logInfo('[customers] 2 -> ' . json_encode($customers));
        $customers = [
            'test1' => 1,
            'test2' => 2,
        ];
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
