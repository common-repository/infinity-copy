<?php

if (!class_exists('WPInfinityCopyPost'))
{
    class WPInfinityCopyPost extends WPInfinityCopyApi
    {
        private static $controllerName = "post";
        private static $route = "/posts";

        public static function get(WP_REST_Request $request)
        {
            if (isset($request['post_type'])) {
                $controller = new WP_REST_Posts_Controller(sanitize_text_field($request['post_type']));
            } else {
                $controller = new WP_REST_Posts_Controller(self::$controllerName);
            }

            return $controller->get_items($request);
        }

        public static function create(WP_REST_Request $request)
        {
            $postType = self::$controllerName;

            if (isset($request['post_type']) && $request['post_type']) {
                $postType = $request['post_type'];
            }

            $controller = new WP_REST_Posts_Controller($postType);

            return $controller->create_item($request);
        }

        public static function publicItemSchema()
        {
            $posts_controller = new WP_REST_Posts_Controller(self::$controllerName);

            return $posts_controller->get_public_item_schema();
        }

        public static function endpointsPermissionCheck()
        {
            return parent::checkAPIKeyAuth();
        }

        public static function registerEndpoints()
        {
            $controller = new WP_REST_Posts_Controller(self::$controllerName);

            $args = [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [__CLASS__, 'get'],
                    'permission_callback' => [__CLASS__, 'endpointsPermissionCheck'],
                    'args'                => $controller->get_collection_params()
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [__CLASS__, 'create'],
                    'permission_callback' => [__CLASS__, 'endpointsPermissionCheck'],
                    'args'                => $controller->get_endpoint_args_for_item_schema()
                ],
                'schema' => [__CLASS__, 'publicItemSchema']
            ];

            register_rest_route(INFINITY_COPY_API_NAMESPACE, self::$route, $args);
        }
    }
}