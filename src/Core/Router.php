<?php
/**
 * Created by PhpStorm.
 * User: hoffmann
 * Date: 09.02.18
 * Time: 17:55
 */

namespace App\Core;


use App\Controllers\SearchController;

class Router
{
    static public $route;

    static public function start()
    {
        self::$route = (object) parse_url($_SERVER['REQUEST_URI']);
        self::$route->path = ltrim(self::$route->path, '/');

        $selected_search_action = self::checkAndSelectAction();
        self::checkGetQuery();
        self::executeSelectedAction($selected_search_action);
    }

    private function checkAndSelectAction() {
        $standard_action = 'google';

        if (self::$route->path != '' && count(explode('/', self::$route->path)) <= 1) {
            if (self::$route->path == '404') {
                http_response_code(404);

                exit("<h1>Page not found</h1>\n<p>Go to <a href='/google'>the main page</a></p> ");
            } else {
                return self::$route->path . 'Action';
            }
        } else {
            header('Location: /' . $standard_action);
        }
        return false;
    }

    private function checkGetQuery() {
        if (property_exists(self::$route, 'query')) {
            parse_str(self::$route->query, $params);

            if (key($params) != 'search_query' || $params['search_query'] == '') {
                header('Location: /' . self::$route->path);
            } elseif (count($params) > 1) {
                header('Location: /' . self::$route->path . '?search_query=' . urlencode($params['search_query']));
            }
        }
    }

    private function executeSelectedAction(string $action) {
        $controller = new SearchController();

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            header('Location:/404');
        }
    }
}