<?php
class Router {
    private $routes = [];
    private $notFound;

    public function get($path, $callback) {
        if (isset($this->routes['GET'][$path])) {
            die("Error: Ruta GET '$path' ya definida.<br>");
        }
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        if (isset($this->routes['POST'][$path])) {
            die("Error: Ruta POST '$path' ya definida.<br>");
        }
        $this->routes['POST'][$path] = $callback;
    }

    public function setNotFound($callback) {
        $this->notFound = $callback;
    }

    public function run() {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    if ($scriptName !== '/') {
        $uri = substr($uri, strlen($scriptName));
    }
    if (empty($uri) || $uri === '') {
        $uri = '/';
    }
    
    if (isset($this->routes[$method][$uri])) {
        return $this->executeCallback($this->routes[$method][$uri]);
    }
    if ($this->notFound) {
        return $this->executeCallback($this->notFound);
    }
    http_response_code(404);
    echo "404 Not Found";
}


    private function executeCallback($callback) {
    if (is_callable($callback)) {
        return call_user_func($callback);
    }
    if (is_string($callback) && strpos($callback, '@') !== false) {
        list($controller, $method) = explode('@', $callback);
        if (class_exists($controller)) {
            $ctrlInstance = new $controller();
            if (method_exists($ctrlInstance, $method)) {
                return call_user_func([$ctrlInstance, $method]);
            } else {
                throw new Exception("El método $method no existe en $controller");
            }
        } else {
            throw new Exception("La clase $controller no existe");
        }
        throw new Exception("No se pudo resolver $callback");
    }
    throw new Exception("Callback inválido");
}

}
?>