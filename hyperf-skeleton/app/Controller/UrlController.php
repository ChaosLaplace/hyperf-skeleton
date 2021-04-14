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
use Psr\Container\ContainerInterface;

class UrlController
{
    private $request;
    private $redis;

    public function __construct(RequestInterface $requestInterface, ContainerInterface $container)
    {
        $this->request = $requestInterface;
        $this->redis = $container->get(\Redis::class);
    }
    // 切換 URL
    public function urlChange()
    {
        // 存在则返回，不存在则返回默认值 null
        $a = $this->request->query('a'); // xl_jc

        $url_change = [];
        if ($this->redis->exists('url_change')) {
            // redis
            try {
                $url_change_count = [];
                $count = $this->redis->hLen('url_change') - 1;
                for ($i = 0; $i <= $count; ++$i) {
                    $url_change_count[] = $i;
                }

                $url_change_List = $this->redis->hMget('url_change', $url_change_count);
                foreach ($url_change_List as $v) {
                    $url_change[] = $v;
                }
                // $this->redis->expire('url_change', 60000);
                // $redisGet = $this->redis->get('url_change');
                // $url_change_List = json_decode($redisGet);
            } catch (\Throwable $exception) {
                return ['[redis exception]', $exception];
            }
        } else {
            // db
            try {
                $url_change_list = Db::table('url_change')->where('switch', 1)->pluck('url');
                foreach ($url_change_list as $v) {
                    $url_change[] = $v;
                }
                // $this->redis->mset($url_change);
                $this->redis->hMset('url_change', $url_change);
                // $this->redis->set('url_change', json_encode($url_change), 60000);
            } catch (\Throwable $exception) {
                return ['[db exception]', $exception];
            }
        }

        shuffle($url_change);
        if ($a === 'xl') { //线路选择
            $duankou = array(
                '8082',
                '8083',
                '8084',
                '8085',
                '8086',
                '8087',
                '8088',
            );
            $urls = array();
            for($i=10;$i<40;$i++){
                array_push($urls,'https://1fa'.$i.'.com:'.$duankou[rand(0,(count($duankou)-1))]);
            }
            $urls = $url_change;

            $urls = array( //线路列表
                array('name' => '线路1', 'url_name' => $this->explode_url_name($urls[0]), 'url' => $urls[0]),
                array('name' => '线路2', 'url_name' => $this->explode_url_name($urls[1]), 'url' => $urls[1]),
                array('name' => '线路3', 'url_name' => $this->explode_url_name($urls[2]), 'url' => $urls[2]),
                array('name' => '线路4', 'url_name' => $this->explode_url_name($urls[3]), 'url' => $urls[3]),
                array('name' => '线路5', 'url_name' => $this->explode_url_name($urls[4]), 'url' => $urls[4]),
                // array('name'=>'APP下载','url_name'=>'APP下载','url'=>'http://39.109.104.158:88'),
                // array('name'=>'金管家','url_name'=>'1fa111.com','url'=>'http://1fa111.com'),
                // array('name'=>'活动申请','url_name'=>'1fa000.com','url'=>'http://1fa000.com'),
                // array('name'=>'开奖查询','url_name'=>'1fa66.com','url'=>'https://1fa66.com'),
            );
            $urls = array(
                'xl' => $urls,
                'kf_url' => 'https://chat.meiqia.cn/widget/standalone.html?eid=f0af34d365cf44507455077f4b72eff0', //在线客服
                 'gg' => array( //广告位 为了美观 建议偶数位 广告位
                    array('img' => 'http://18.162.201.217:88/images/jinguanjia.gif', 'url' => 'https://chat.meiqia.cn/widget/standalone.html?eid=f0af34d365cf44507455077f4b72eff0'),
                    array('img' => 'http://18.162.201.217:88/images/jinguanjia.gif', 'url' => 'https://chat.meiqia.cn/widget/standalone.html?eid=f0af34d365cf44507455077f4b72eff0'),
                ),
                'gg2' => array('img' => 'http://18.162.201.217:88/images/appdown.png', 'url' => 'http://18.162.201.217:88/app_down.html'),
            );
        }
        if ($a === 'xl_jc') {
            // 线路列表
            $urls = [];
            foreach($url_change as $v){
                $urls[] = array('url' => $v);
            }

            $urls = array(
                'xl' => $urls,
                'kf_url' => 'https://chat.meiqia.cn/widget/standalone.html?eid=f0af34d365cf44507455077f4b72eff0', //在线客服
                 'gg' => array( //广告位 为了美观 建议偶数位 广告位
                    array('img' => 'http://18.162.201.217:88/images/jinguanjia.gif', 'url' => 'https://chat.meiqia.cn/widget/standalone.html?eid=f0af34d365cf44507455077f4b72eff0'),
                    array('img' => 'http://18.162.201.217:88/images/jinguanjia.gif', 'url' => 'https://chat.meiqia.cn/widget/standalone.html?eid=f0af34d365cf44507455077f4b72eff0'),
                ),
                'gg2' => array('img' => 'http://18.162.201.217:88/images/appdown.png', 'url' => 'http://18.162.201.217:88/app_down.html'),
            );
        }

        return json_encode($urls);
    }

    public function explode_url_name($url)
    {
        $url = explode('//', $url);
        $url = $url[1];
        return $url;
    }
}
