<?php
namespace Core;

use Core\Twig\AppExtensions;

class View
{
    /**
     * @param $template
     * @param array $args
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function renderHtml($template, $args = [])
    {
        static $twig = null;
        if($twig === null) {
            $loader = new \Twig_Loader_Filesystem(APP_PATH.DIRECTORY_SEPARATOR.'Views');
            $twig = new \Twig_Environment($loader);
            $twig->addExtension(new AppExtensions($loader));
        }
        echo $twig->render($template, $args);
    }

    public static function renderJSON(Array $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
