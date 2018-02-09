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
    public function render(string $content_view, \stdClass $data = NULL)
    {
        include __DIR__.'/../Views/base.phtml';
    }
}