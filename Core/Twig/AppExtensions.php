<?php
/**
 * Created by PhpStorm.
 * User: Konrad
 * Date: 2/15/2018
 * Time: 4:59 PM
 */

namespace Core\Twig;

class AppExtensions extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('namedRoute', [$this, 'namedRoute'])
        ];
    }

    public function namedRoute($routeName)
    {
        $router = new \Core\Router();
        return $router->getUrlByRouteName($routeName);
    }
}