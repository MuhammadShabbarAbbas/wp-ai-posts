<?php
/**
 * Plugin Name: WP AI Posts
 * Description: This plugin creates posts using Chat GPT with given prompt
 * Author: ODES
 * Author URI: https://odes.pk/
 * Version: 1.0.0
 * Text Domain: 'wpaiposts'
 * Domain Path: languages
 * License: GPL2 or later.
 *
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Main Class.
 *
 * @since 1.0.0
 */
final class WP_AI_POSTS
{
    /**
     * @var The one true instance
     * @since 1.0.0
     */
    protected static $_instance = null;

    public $settings = null;

    public $version = '1.0.0';

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->localisation();
        $this->init();

        do_action('wpaiposts_management_loaded');
    }

    /**
     * Define Constants.
     * @since  1.0.0
     */
    private function define_constants()
    {
        $this->define('WP_AI_POSTS_DIR', plugin_dir_path(__FILE__));
        $this->define('WP_AI_POSTS_URL', plugin_dir_url(__FILE__));
        $this->define('WP_AI_POSTS_BASENAME', plugin_basename(__FILE__));
        $this->define('WP_AI_POSTS_VERSION', $this->version);
        $this->define('WP_AI_POSTS_CHAT_GPT_API', 'https://api.openai.com/v1/chat/');
    }

    /**
     * Define constant if not already set.
     * @since  1.0.0
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Include required files.
     * @since  1.0.0
     */
    public function includes()
    {
        /**
         * Lib
         */
        include_once 'includes/lib/class-settings-api.php';

        /**
         * settings class
         */
        include_once 'includes/class-settings.php';

        /**
         * Meta boxes
         */
        include_once 'includes/class-metabox.php';
        /**
         * setup
         */
        include_once 'includes/class-setup.php';
        /**
         * menus
         */
        include_once 'includes/class-menus.php';
        /**
         * ajax handler
         */
        include_once 'includes/class-create-posts.php';
        /**
         * functions
         */
        include_once 'includes/functions.php';

    }

    /**
     * Load Localisation files.
     * @since  1.0.0
     */
    public function localisation()
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'wpaiposts');

        load_textdomain('wpaiposts', WP_LANG_DIR . '/wp-ai-posts/wp-ai-posts-' . $locale . '.mo');
        load_plugin_textdomain('wpaiposts', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }

    public function init()
    {
        $this->settings = new WP_AI_POSTS_Settings();
        register_activation_hook(__FILE__, [$this, 'activate']);
    }


    /**
     * Main Instance.
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function activate()
    {

    }

    /**
     * Throw error on object clone.
     *
     * @return void
     * @since 1.0.0
     * @access protected
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'wpaiposts'), '1.0.0');
    }

    /**
     * Disable unserializing of the class.
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'wpaiposts'), '1.0.0');
    }

}

/**
 * Run the plugin.
 */
function wp_ai_posts()
{
    return WP_AI_POSTS::instance();
}

wp_ai_posts();
