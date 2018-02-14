<?php
/**
 * Created by PhpStorm.
 * User: hoffmann
 * Date: 09.02.18
 * Time: 9:43
 */

namespace App\Core;


class Controller
{
    public $configs;
    public $view;

    public function __construct()
    {
        $this->configs = json_decode(file_get_contents(__DIR__.'/../../configs/search_configs.json'));

        $this->view = new View();
        $this->view->configs = $this->configs;
    }
}