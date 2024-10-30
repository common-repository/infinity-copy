<?php

if (!class_exists('WPInfinityCopyApi'))
{
    abstract class WPInfinityCopyApi
    {
        protected static function getUserIdBytoken($token)
        {
            $tokensList = get_option(INFINITY_COPY_API_KEY);

            if (!is_array($tokensList)) {
                return null;
            }

            if ($userEmail = array_search($token, $tokensList)) {
                if ($user = get_user_by('email', $userEmail)) {
                    return $user->ID;
                }
            }

            return null;
        }

        protected static function checkAPIKeyAuth()
        {
            $auth = isset($_SERVER['HTTP_TOKEN']) ? sanitize_text_field($_SERVER['HTTP_TOKEN']) : false;
            $tokensList = get_option(INFINITY_COPY_API_KEY, true );

            if ($userId = self::getUserIdBytoken($auth)) {
                wp_set_current_user($userId);
            }

            return $userId && is_array($tokensList) && in_array($auth, $tokensList);
        }

        abstract protected static function endpointsPermissionCheck();

        abstract protected static function registerEndpoints();
    }
}