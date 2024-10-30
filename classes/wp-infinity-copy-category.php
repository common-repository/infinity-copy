<?php

if (!class_exists('WPInfinityCopyCategory'))
{
    class WPInfinityCopyCategory extends WPInfinityCopyApi
    {
        private static $controllerName = "category";
        private static $route = "/categories";

        public static function get(WP_REST_Request $request)
        {
            $controller = new WP_REST_Terms_Controller(self::$controllerName);

            return $controller->get_items($request);
        }

        public static function endpointsPermissionCheck()
        {
            return parent::checkAPIKeyAuth();
        }

        public static function registerEndpoints()
        {
            $controller = new WP_REST_Terms_Controller(self::$controllerName);

            $args = [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [__CLASS__, 'get'],
                'permission_callback' => [__CLASS__, 'endpointsPermissionCheck'],
                'args'                => $controller->get_collection_params()
            ];

            register_rest_route(INFINITY_COPY_API_NAMESPACE, self::$route, $args);
        }
    }
}