<?php
namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller
{
    protected function before()
    {

    }

    protected function after()
    {

    }

    public function indexAction()
    {
       View::renderHtml('Home/index.html',[
            'name' =>       'Dave',
            'colours' =>    ['red','green','blue']
        ]);
    }
}
