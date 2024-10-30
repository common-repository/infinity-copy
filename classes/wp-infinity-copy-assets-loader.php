<?php

if (!class_exists('WPInfinityCopyAssetsLoader'))
{
    class WPInfinityCopyAssetsLoader
    {
        private static $initiated = false;
        private static $assetsSrcUrl = "";

        public static function init()
        {
            self::$assetsSrcUrl = INFINITY_COPY_PLUGIN_DIR . '/assets';

            if (!self::$initiated) {
                self::registerHooks();
            }
        }

        public static function registerHooks()
        {
            self::$initiated = true;

            $actionsToRegister = [
                "admin_enqueue_scripts" => "loadFormIntegrationStyle"
            ];

            foreach ($actionsToRegister as $action => $function) {
                add_action($action, [
                    __CLASS__, // Main Class
                    $function // Function to register
                ]);
            }
        }

        public static function loadFormIntegrationStyle()
        {
            wp_register_style('infinity-copy', plugins_url('css/style.css', self::$assetsSrcUrl . '/css'));
            wp_enqueue_style('infinity-copy');
        }
    }
}
