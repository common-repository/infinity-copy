<?php

/**
 * Plugin Name: Infinity Copy - Escritor e Gerador de Conteúdo por Inteligência Artificial
 * Description: O novo plugin WordPress da Infinity Copy torna a criação de conteúdo mais fácil do que nunca - basta gerar o conteúdo em nosso Escritor e enviá-lo diretamente para o seu site WordPress com apenas um clique!
 * Author: <a href="https://infinitycopy.ai/">Infinity Copy</a>
 * Author URI: https://infinitycopy.ai/
 * Text Domain: infinitycopy
 * Version: 1.0.2
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;
if (!function_exists('add_action')) exit;

// Define consts
define('INFINITY_COPY_VERSION', '1.0.2');
define('INFINITY_COPY_MINIMUM_WP_VERSION', '5.0');
define('INFINITY_COPY_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('INFINITY_COPY_API_KEY', 'infinity_copy_api_key');
define('INFINITY_COPY_API_NAMESPACE', 'infinitycopy/v1');
define('INFINITY_COPY_APP_CONNECT_URL', 'https://app.infinitycopy.ai/wordpress-authentication/');
define('INFINITY_COPY_API_URL', 'https://api.infinitycopy.ai/v1/integrations/wordpress/');

// Required files
require_once(INFINITY_COPY_PLUGIN_DIR . '/classes/wp-infinity-copy-api.php');
require_once(INFINITY_COPY_PLUGIN_DIR . '/classes/wp-infinity-copy-category.php');
require_once(INFINITY_COPY_PLUGIN_DIR . '/classes/wp-infinity-copy-post.php');
require_once(INFINITY_COPY_PLUGIN_DIR . '/classes/wp-infinity-copy.php');
require_once(INFINITY_COPY_PLUGIN_DIR . '/classes/wp-infinity-copy-assets-loader.php');

// Register activation
register_activation_hook(INFINITY_COPY_PLUGIN_DIR, ['WPInfinityCopy', 'activation']);

// Register deactivation
register_deactivation_hook(INFINITY_COPY_PLUGIN_DIR, ['WPInfinityCopy', 'deactivation']);

// Init all
add_action('init', ['WPInfinityCopy', 'init']);
add_action('init', ['WPInfinityCopyAssetsLoader', 'init']);

