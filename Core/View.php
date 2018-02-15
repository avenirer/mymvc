<?php
namespace Core;

class View
{
    /*
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);
        $file = APP_PATH.DIRECTORY_SEPARATOR."Views/".$view;

        if (is_readable($file)) {
            require $file;
        }
        else {
            throw new \Exception("$file not found");
        }
    }
    */

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
        }
        echo $twig->render($template, $args);
    }

    public static function renderJSON(Array $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
