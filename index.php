<?php
/**
 * Created by PhpStorm.
 * User: hoffmann
 * Date: 09.02.18
 * Time: 9:30
 */

ini_set('display_errors', 1);

require_once __DIR__.'/vendor/autoload.php';

(new App\Controllers\MainController)->indexAction();