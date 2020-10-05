<?php

declare(strict_types=1);

namespace Vendor\kernel {

    use Exception;
    use Vendor\db\Db;

    /**
     * System core
     *
     * @package Vendor\kernel
     */
    final class Kernel
    {
        /**
         * @var object main component objects
         */
        public static $app;

        /**
         * @var array routing requests
         */
        public $request = [];

        /**
         * Loads the application
         *
         * @param string $appName application Name
         * @param string $controllerName controller name
         * @param string $actionName action name
         * @throws Exception
         */
        public function requestApp(string $appName, string $controllerName, string $actionName): void
        {
            $app = "\App\\$appName\App";
            if (class_exists($app)) {
                $app = new $app;
                if (!($app instanceof AppLoader)) {
                    unset($app);
                    throw new Exception('Class does`not instance of AppLoader');
                }

                if (!empty($app->getControllers()[$controllerName]) && class_exists(
                        $app->getControllers()[$controllerName]
                    )) {
                    $controller = $app->getControllers()[$controllerName];
                    $controller = new $controller;
                    $action = 'action' . preg_replace('/-/', '', ucwords(preg_replace("/\s+/", '', $actionName), "-"));

                    if (!method_exists($controller, $action)) {
                        throw new Exception('Action not found');
                    } else {
                        $controller->$action();
                    }
                } else {
                    throw new Exception('Class controller or route not found in-app');
                }
            } else {
                throw new Exception('App not found');
            }
        }

        public function __construct()
        {
            try {
                //Loading components
                self::$app = (object)['db' => new Db()];
                $this->request = array_filter(
                    explode('/', $_SERVER['PATH_INFO'] ?? '') ?? [],
                    function ($element) {
                        return $element !== '';
                    }
                );

                //Application download
                if (isset($this->request[1])) {
                    $this->requestApp($this->request[1], $this->request[2] ?? '', $this->request[3] ?? '');
                }
            } catch (Exception $e) {
                echo sprintf('[%s:%s] %s', $e->getFile(), $e->getLine(), $e->getMessage());
            }
        }
    }
}