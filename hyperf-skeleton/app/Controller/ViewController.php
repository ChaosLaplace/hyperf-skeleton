<?php

declare (strict_types = 1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\View\RenderInterface;

/**
 * @AutoController
 */
class ViewController
{
    public function index(RenderInterface $render)
    {
        $result = array(
            'name' => '',
        );
        return $render->render('index', $result);
    }
}
