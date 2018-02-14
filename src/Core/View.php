<?php
/**
 * Created by PhpStorm.
 * User: hoffmann
 * Date: 09.02.18
 * Time: 9:43
 */

namespace App\Core;


class View
{
    public $configs;
    public $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }

    public function render(string $content_view)
    {
        $configs = $this->configs;

        $data = $this->data;
        $data->route = Router::$route;

        include __DIR__.'/../Views/base.phtml';
    }
}