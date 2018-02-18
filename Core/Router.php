<?php
namespace Core;
use App\Config\Routes;
class Router
{
    protected $routes = [];

    protected $params = [];
    protected $controller = false;

    function __construct()
    {
        $routes = Routes::ROUTES;
        foreach($routes as $route => $params) {
            $this->add($route,$params);
        }
    }

    /**
     * Add a route. The format should be
     * @param string $route
     * @param array $params The params should be an array with the following keys: 'namespace' (if controller is in
     * a directory), 'controller','action'
     * Examples:
     * $router->add('posts',['controller' => 'Posts','action'=>'index']);
     * $router->add('{controller}/{action}');
     * $router->add('{controller}/{id:\d+}/{action}');
     * $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
     *
     * @return void
     */
    public function add($route, $params = [])
    {
        $params['url'] = $route;
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiter, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Return all the defined routes
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Return an url by specifying a route name
     * @param $routeName
     * @return string
     */
    public function getUrlByRouteName($routeName)
    {
        $routeKey = array_search($routeName, array_column($this->routes,'routeName','url'));
        if ($routeKey !== false) {
            return BASE_URL.$routeKey;
        }
        return BASE_URL;
    }

    /**
     * The function tries to find a match between the url and the defined routes. If it finds a match,
     * it will populate $this->params with the match (controller and action) and will return true.
     * @param string $url
     * @return bool
     */
    public function match($url)
    {
        foreach($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $url
     * @throws \Exception
     */
    public function dispatch($url)
    {
        // first we remove the query string variables from the url (those are still available from $_GET)
        $url = $this->removeQueryStringVariables($url);
        // then we see if there is a match for the url
        if ($this->match($url)) {
            $this->getController();


            if (class_exists($this->controller)) {
                $controllerObj = new $this->controller($this->params);
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                $controllerObj->$action();
            }
            else {
                throw new \Exception("Controller class $this->controller not found", 404);
            }
        }
        else {
            throw new \Exception("No route matched.", 404);
        }

    }

    protected function getController()
    {
        $controller = $this->params['controller'];

        /* we convert to studly caps for the eventuality that the route was not defined (in which case
         * a default routing {controller}/{action} will be done if it was defined inside routes...
         */
        $controller = $this->convertToStudlyCaps($controller);

        // we look if a namespace was defined and act accordingly
        $controller = $this->getNamespace() . $controller;

        $this->controller = $controller;
    }

    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ','',ucwords(str_replace('-',' ',$string)));
    }

    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables($url)
    {
        if ($url !='') {
            $parts = explode('&', $url, 2);
            if(strpos($parts[0], '=') === false) {
                $url = $parts[0];
            }
            else {
                $url = '';
            }
        }
        return $url;
    }

    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }

    public function getParams()
    {
        return $this->params;
    }
}
