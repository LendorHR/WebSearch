<?php
/**
 * Created by PhpStorm.
 * User: hoffmann
 * Date: 09.02.18
 * Time: 9:51
 */

namespace App\Controllers;


use App\Core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $this->view->render('main/index.phtml');
    }
}