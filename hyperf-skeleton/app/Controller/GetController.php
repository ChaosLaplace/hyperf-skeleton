<?php

declare (strict_types = 1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;

/**
 * @AutoController
 */
class GetController
{
    public function log(RequestInterface $request, RenderInterface $render)
    {
        $url = $request->query('url', '');
        $data = file_get_contents($url);

        $result = array(
            'name' => 'Get Log',
            'data' => $data,
        );
        return $render->render('log', $result);
    }
}
