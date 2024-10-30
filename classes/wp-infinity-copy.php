<?php

if (!class_exists('WPInfinityCopy'))
{
    class WPInfinityCopy
    {
        private static $initiated = false;

        public static function init()
        {
            if (!self::$initiated) {
                self::registerHooks();
            }
        }

        public static function registerHooks()
        {
            self::$initiated = true;

            $actionsToRegister = [
                "admin_menu"    => "loadSettingsMenu",
                "admin_init"    => "loadSettings",
                "rest_api_init" => "registerEndpoints"
            ];

            foreach ($actionsToRegister as $action => $function) {
                add_action($action, [
                    __CLASS__, // Main Class
                    $function // Function to register
                ]);
            }
        }

        public static function activation()
        {
            if (version_compare($GLOBALS['wp_version'], INFINITY_COPY_MINIMUM_WP_VERSION, '<')) {
                load_plugin_textdomain('infinity-copy');
            } elseif (!empty($_SERVER['SCRIPT_NAME']) && false !== strpos($_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php')) {
                add_option('Activated_InfinityCopy', true );
            }
        }

        public static function deactivation()
        {
            // delete_option(INFINITY_COPY_API_KEY_OPTION);
        }

        public static function loadSettingsMenu()
        {
            add_options_page('Configurações Infinity Copy', 'Infinity Copy', 'manage_options', 'infinity-copy', [
                __CLASS__,
                'loadSettingsPage'
            ]);
        }

        public static function loadSettingsPage()
        {
            include INFINITY_COPY_PLUGIN_DIR . '/includes/settings-page.php';
        }

        public static function loadSettings()
        {
            register_setting('infinity-copy', "");
        }

        public static function checkDomainAuthorization($domain, $userId, $token)
        {
            $headers = [
                'Content-Type' => 'application/json'
            ];

            $body = wp_json_encode(compact(
                'domain',
                'token'
            ));

            $options = [
                'headers'     => $headers,
                'body'        => $body,
                'data_format' => 'body'
            ];

            $request = wp_remote_post(INFINITY_COPY_API_URL . $userId . "/status", $options);
            $response = wp_remote_retrieve_body($request);

            return (bool) json_decode($response, true);
        }

        public static function endpointsPermissionCheck(){}

        public static function registerEndpoints()
        {
            WPInfinityCopyCategory::registerEndpoints();
            WPInfinityCopyPost::registerEndpoints();
        }
    }
}