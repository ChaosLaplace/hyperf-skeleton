<?php

declare (strict_types = 1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;

/**
 * @AutoController
 */
class LogController
{
    private $testLog = '/hyperf-skeleton/runtime/logs/hyperf.log';

    public function logGet(RequestInterface $request, RenderInterface $render)
    {
        $url = $request->query('url', '');
        $data = file_get_contents($url);

        $result = array(
            'name' => 'Get Log',
            'data' => $data,
        );
        return $render->render('log', $result);
    }

    public function logTestGet(RenderInterface $render)
    {
        $data = file_get_contents($this->testLog);

        $result = array(
            'name' => 'Get TestLog',
            'data' => $data,
        );
        return $render->render('log', $result);
    }

    public function logDelete()
    {
        $data = '';
        file_put_contents($this->testLog, $data);
    }
}
