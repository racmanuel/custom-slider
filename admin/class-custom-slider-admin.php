<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://racmanuel.dev/
 * @since      1.0.0
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks to
 * enqueue the admin-facing stylesheet and JavaScript.
 * As you add hooks and methods, update this description.
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/admin
 * @author     racmanuel <developer@racmanuel.dev>
 */
class Custom_Slider_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The unique prefix of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_prefix    The string used to uniquely prefix technical functions of this plugin.
     */
    private $plugin_prefix;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of this plugin.
     * @param      string $plugin_prefix    The unique prefix of this plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct($plugin_name, $plugin_prefix, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->plugin_prefix = $plugin_prefix;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_styles($hook_suffix)
    {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/custom-slider-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_scripts($hook_suffix)
    {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/custom-slider-admin.js', array('jquery'), $this->version, false);

    }

    public function custom_slider_options_metabox()
    {

        /**
         * Registers options page menu item and form.
         */
        $cmb_options = new_cmb2_box(array(
            'id' => 'custom_slider_option_metabox',
            'title' => esc_html__('Custom Slider', 'myprefix'),
            'object_types' => array('options-page'),

            /*
             * The following parameters are specific to the options-page box
             * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
             */

            'option_key' => 'custom_slider_options', // The option key and admin menu page slug.
            'icon_url' => 'dashicons-cover-image', // Menu icon. Only applicable if 'parent_slug' is left empty.
            // 'menu_title'      => esc_html__( 'Options', 'myprefix' ), // Falls back to 'title' (above).
            // 'parent_slug'     => 'themes.php', // Make options page a submenu item of the themes menu.
            'capability' => 'manage_options', // Cap required to view options-page.
            // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
            // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
            // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
            'save_button' => esc_html__('Guardar', 'myprefix'), // The text for the options-page save button. Defaults to 'Save'.
        ));

        /*
         * Options fields ids only need
         * to be unique within this box.
         * Prefix is not needed.
         */
        $cmb_options->add_field(array(
            'name' => 'Color de flechas de Navegacion',
            'id' => 'custom_slider_arrow_colorpicker',
            'type' => 'colorpicker',
            'default' => '#ffffff',
        ));

        $cmb_options->add_field(array(
            'name' => 'Color de puntos de Paginacion',
            'id' => 'custom_slider_dots_colorpicker',
            'type' => 'colorpicker',
            'default' => '#ffffff',
        ));

        $group_field_id = $cmb_options->add_field(array(
            'id' => 'custom_slider_repeat_group',
            'type' => 'group',
            'description' => __('Inserta las imagenes que apareceran en el Slider', 'cmb2'),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options' => array(
                'group_title' => __('Imagen {#}', 'cmb2'), // since version 1.1.4, {#} gets replaced by row number
                'add_button' => __('Añadir otra imagen', 'cmb2'),
                'remove_button' => __('Eliminar imagen', 'cmb2'),
                'sortable' => true,
                // 'closed'         => true, // true to have the groups closed by default
                'remove_confirm' => esc_html__('Estas seguro que quieres eliminarla?', 'cmb2'), // Performs confirmation before removing group.
            ),
        ));

        $cmb_options->add_group_field($group_field_id, array(
            'name' => 'Imagen',
            'id' => 'image',
            'type' => 'file',
            'text' => array(
                'add_upload_file_text' => 'Añadir imagen', // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                ),
            ),
            'preview_size' => 'thumbnail', // Image size to use when previewing in the admin.
        ));
    }

    public function add_shortcode_to_homepage($content)
    {
        // Verificar si estamos en la página principal
        if (is_front_page() && is_main_query()) {
            // Agregar el shortcode al contenido existente
            $content .= do_shortcode('[custom_slider_shortcode]');
        }
        return $content;
    }
}
