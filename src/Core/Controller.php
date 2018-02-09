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
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }
}