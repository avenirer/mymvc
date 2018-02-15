<?php
/**
 * Created by PhpStorm.
 * User: Konrad
 * Date: 2/15/2018
 * Time: 12:52 PM
 */

namespace App\Config;


class Routes
{
    const ROUTES = [
        '' => [
            'controller' => 'Home',
            'action'=>'index'],
        'posts' => [
            'controller' => 'Posts',
            'action'=>'index'],
        '{controller}/{action}' => [],

        '{controller}/{id:\d+}/{action}' => [],

        'admin/{controller}/{action}' => [
            'namespace' => 'Admin']
        ];

}