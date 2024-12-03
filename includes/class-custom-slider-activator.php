<?php
/**
 * Fired during plugin activation
 *
 * @link       https://racmanuel.dev/
 * @since      1.0.0
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @todo This should probably be in one class together with Deactivator Class.
 * @since      1.0.0
 * @package    Custom_Slider
 * @subpackage Custom_Slider/includes
 * @author     racmanuel <developer@racmanuel.dev>
 */
class Custom_Slider_Activator
{

    /**
     * The $_REQUEST during plugin activation.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $request    The $_REQUEST array during plugin activation.
     */
    private static $request = array();

    /**
     * The $_REQUEST['plugin'] during plugin activation.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin    The $_REQUEST['plugin'] value during plugin activation.
     */
    private static $plugin = CUSTOM_SLIDER_BASE_NAME;

    /**
     * Activate the plugin.
     *
     * Checks if the plugin was (safely) activated.
     * Place to add any custom action during plugin activation.
     *
     * @since    1.0.0
     */
    public static function activate()
    {

        if (false === self::get_request()
            || false === self::validate_request(self::$plugin)
            || false === self::check_caps()
        ) {
            if (isset($_REQUEST['plugin'])) {
                if (!check_admin_referer('activate-plugin_' . self::$request['plugin'])) {
                    exit;
                }
            } elseif (isset($_REQUEST['checked'])) {
                if (!check_admin_referer('bulk-plugins')) {
                    exit;
                }
            }
        }

        /**
         * The plugin is now safely activated.
         * Perform your activation actions here.
         */
		self::insert_shortcode_into_homepage();
    }

    /**
     * Get the request.
     *
     * Gets the $_REQUEST array and checks if necessary keys are set.
     * Populates self::request with necessary and sanitized values.
     *
     * @since    1.0.0
     * @return bool|array false or self::$request array.
     */
    private static function get_request()
    {

        if (!empty($_REQUEST)
            && isset($_REQUEST['_wpnonce'])
            && isset($_REQUEST['action'])
        ) {
            if (isset($_REQUEST['plugin'])) {
                if (false !== wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'activate-plugin_' . sanitize_text_field(wp_unslash($_REQUEST['plugin'])))) {

                    self::$request['plugin'] = sanitize_text_field(wp_unslash($_REQUEST['plugin']));
                    self::$request['action'] = sanitize_text_field(wp_unslash($_REQUEST['action']));

                    return self::$request;

                }
            } elseif (isset($_REQUEST['checked'])) {
                if (false !== wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'bulk-plugins')) {

                    self::$request['action'] = sanitize_text_field(wp_unslash($_REQUEST['action']));
                    self::$request['plugins'] = array_map('sanitize_text_field', wp_unslash($_REQUEST['checked']));

                    return self::$request;

                }
            }
        } else {

            return false;
        }

    }

    /**
     * Validate the Request data.
     *
     * Validates the $_REQUESTed data is matching this plugin and action.
     *
     * @since    1.0.0
     * @param string $plugin The Plugin folder/name.php.
     * @return bool false if either plugin or action does not match, else true.
     */
    private static function validate_request($plugin)
    {

        if (isset(self::$request['plugin'])
            && $plugin === self::$request['plugin']
            && 'activate' === self::$request['action']
        ) {

            return true;

        } elseif (isset(self::$request['plugins'])
            && 'activate-selected' === self::$request['action']
            && in_array($plugin, self::$request['plugins'])
        ) {
            return true;
        }

        return false;

    }

    /**
     * Check Capabilities.
     *
     * We want no one else but users with activate_plugins or above to be able to active this plugin.
     *
     * @since    1.0.0
     * @return bool false if no caps, else true.
     */
    private static function check_caps()
    {

        if (current_user_can('activate_plugins')) {
            return true;
        }

        return false;

    }

    private static function insert_shortcode_into_homepage()
    {
        // Obtén la ID de la página principal
        $homepage_id = get_option('page_on_front');
        if ($homepage_id) {
            // Obtén el contenido actual de la página
            $homepage_content = get_post_field('post_content', $homepage_id);
            // Verifica que el shortcode no esté ya incluido
            if (strpos($homepage_content, '[custom_slider_shortcode]') === false) {
                // Agrega el shortcode al inicio del contenido
                $homepage_content = "[custom_slider_shortcode]\n" . $homepage_content;
                // Actualiza la página con el nuevo contenido
                wp_update_post(array(
                    'ID' => $homepage_id,
                    'post_content' => $homepage_content,
                ));
            }
        }
    }
}
