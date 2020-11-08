<?php

use Interfaces\FrontControllerInterface;

class FrontController implements FrontControllerInterface
{
    const DEFAULT_CONTROLLER = "App";
    const DEFAULT_ACTION     = "run";

    protected $controller    = self::DEFAULT_CONTROLLER;
    protected $action        = self::DEFAULT_ACTION;
    protected $params        = array();
    protected $basePath      = __DIR__;

    public function __construct(array $options = array()) {
        if (empty($options)) {
            $this->parseUri();
        }
        else {
            if (isset($options["controller"])) {
                $this->setController($options["controller"]);
            }
            if (isset($options["action"])) {
                $this->setAction($options["action"]);
            }
            if (isset($options["params"])) {
                $this->setParams($options["params"]);
            }
        }
    }

    protected function parseUri() {

        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

//        $path = preg_replace('/[^a-zA-Z0-9]/', "", $path);


        if (strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }
        @list($controller, $action, $params) = explode("/", $path, 3);
        if (isset($controller) && $controller !== '') {
            $this->setController($controller);
        } else {
            $this->setController(self::DEFAULT_CONTROLLER);
        }

        if (isset($action)) {
            $this->setAction($action);
        } else {
            $this->setAction(self::DEFAULT_ACTION);
        }

        if (isset($params)) {
            $this->setParams(explode("/", $params));
        }
    }

    public function setController($controller) {
        if ($controller != FrontController::DEFAULT_CONTROLLER) {
            $controller = 'Controllers\\' . ucfirst(strtolower($controller)) . 'Controller';
            if (!class_exists($controller)) {
                throw new InvalidArgumentException(
                    "The action controller '$controller' has not been defined");
            }
        }
        $this->controller = $controller;
        return $this;
    }

    public function setAction($action) {
        try {
            $reflector = new ReflectionClass($this->controller);
        } catch (\ReflectionException $e) {
            echo 'Error message: ' . $e;
        }
        if (!$reflector->hasMethod($action)) {
            throw new InvalidArgumentException(
                "The controller action '$action' has been not defined.");
        }
        $this->action = $action;
        return $this;
    }

    public function setParams(array $params) {
        $this->params = $params;
        return $this;
    }

    public function run() {
        session_start();
        call_user_func_array(array(new $this->controller, $this->action), $this->params);
    }
}