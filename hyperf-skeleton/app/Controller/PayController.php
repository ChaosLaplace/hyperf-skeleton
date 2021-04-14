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
use Hyperf\Logger\LoggerFactory;

class PayController
{
    private $logger;
    private $request;
    // 金额单位 元 1 分 100
    const cent = 100;

    public function __construct(LoggerFactory $loggerFactory, RequestInterface $requestInterface)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('test', 'default');
        $this->request = $requestInterface;
    }
    // 下單
    public function payRequest()
    {
        // 存在则返回，不存在则返回默认值 null
        $mchId = $this->request->input('mchId'); // 商戶號
        $orderId = $this->request->input('orderId'); // 訂單號
        $amount = $this->request->input('amount'); // 金額
        $payType = $this->request->input('payType'); // 通道編碼
        $apiUrl = 'https://goldelephantpay.com:6280/api'; // 三方URL
        $callbackUrl = 'http://172.17.16.1:9501/test/payNotify'; // 回調URL
        $md5Key = 'c5809078fa6d652e0b0232d552a9d06d37fe819c';
        $desKey = 'dslfksmdf3sd1';

        $busidata = [
            'orderNo' => $orderId,
            'orderAmt' => ($amount * self::cent),
            'orderName' => 'orderName',
            'payMethod' => $payType, // 支付方式
             'userId' => $mchId, // 平台分配商户号
             'userIp' => $this->getIp($request),
            'notifyUrl' => $callbackUrl, // 服务端返回地址(POST返回数据)
             'returnUrl' => $callbackUrl, // 服务端返回地址(POST返回数据)
        ];
        $post = [
            'servicename' => 'order.submit',
            'charset' => 'UTF-8',
            'version' => '1.0',
            'merid' => $mchId,
            'requesttime' => date('YmdHis', time()),
            'busidata' => $busidata,
        ];
        $post['signdata'] = $this->getSign($post, $md5Key);
        // 提交请求
        $curl = $this->curlPostJson($apiUrl, $post);
        if ($curl['code'] === '200') {
            $data = json_decode($curl['data'], true);

            if (isset($data['resultCode']) && $data['resultCode'] === '00') {
                $res = [
                    'url' => $data['payUrl'],
                    'param' => '',
                ];
                return ['200', '下单成功', $data, $desKey];
            } else {
                return ['400', '下单失败 ' . $data['responsemsg'], $data, $desKey];
            }
        }
        return ['400', '请求失败 ' . $curl['msg']];
    }
    // 回調
    public function payNotify()
    {
        $md5Key = 'c5809078fa6d652e0b0232d552a9d06d37fe819c';

        $data = $this->request->all();
        $sign = $this->getSign($data, $md5Key);

        $key = $data['sign'];
        if ($sign === $key && $data['result'] === 'SUCCESS') {
            // 更新訂單狀態 商戶ID 商戶密鑰 商戶訂單號 平台訂單號 金額 回調數據
            // $res = $this->orderUpdate(, ($data['orderAmt'] / self::cent), $data);
            if (!$res || $res['state'] === 0) {
                return ['更新訂單失败 ' . $res['msg']];
            } else {
                echo 'success';
                return ['回調成功'];
            }
        } else {
            if ($sign === $key) {
                return ['回調失败 請確認訂單狀態'];
            }
            return ['簽名不一致 請確認密鑰是否正確'];
        }
    }

    public function logInfo($data = '', $dataArray = [])
    {
        $this->logger->info($data, $dataArray);
    }
    /**
     * 模拟 JSON 提交请求
     */
    public function curlPostJson($url, $param, $timeout = 60)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'content-type=application/json;charset=UTF-8'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        $data = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 502) {
            return $this->resCurl('400', '系统繁忙, 请稍后再试...');
        }

        if (curl_errno($ch)) {
            return $this->resCurl('408', '[三方URL -> ' . $url . ']' . curl_error($ch));
        }
        curl_close($ch);

        return $this->resCurl('200', '', $data);
    }

    public function resCurl($code, $msg = '', $data = '')
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        return $res;
    }
    // 签名
    public function getSign($data, $key)
    {
        $str = 'busidata=' . $data['busidata'];
        ksort($data);
        foreach ($data as $k => $v) {
            if ($k === 'busidata' || $v === '') {
                continue;
            }
            $str .= $k . '=' . $v . '&';
        }
        // $str = rtrim($str, '&');
        $str .= 'key=' . $key;
        return strtoupper(md5($str));
    }
    // IP
    public function getIp()
    {
        $res = $this->request->getServerParams();
        if (isset($res['http_client_ip'])) {
            return $res['http_client_ip'];
        } elseif (isset($res['http_x_real_ip'])) {
            return $res['http_x_real_ip'];
        } elseif (isset($res['http_x_forwarded_for'])) {
            // 部分CDN会获取多层代理IP，所以转成数组取第一个值
            $arr = explode(',', $res['http_x_forwarded_for']);
            return $arr[0];
        } else {
            return $res['remote_addr'];
        }
    }
}
