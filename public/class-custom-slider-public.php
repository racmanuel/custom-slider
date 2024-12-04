<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://racmanuel.dev/
 * @since      1.0.0
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks to
 * enqueue the public-facing stylesheet and JavaScript.
 * As you add hooks and methods, update this description.
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/public
 * @author     racmanuel <developer@racmanuel.dev>
 */
class Custom_Slider_Public
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
     * @param      string $plugin_name      The name of the plugin.
     * @param      string $plugin_prefix          The unique prefix of this plugin.
     * @param      string $version          The version of this plugin.
     */
    public function __construct($plugin_name, $plugin_prefix, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->plugin_prefix = $plugin_prefix;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/custom-slider-public.css', array(), time(), 'all');
        wp_register_style($this->plugin_name . '-slider', plugin_dir_url(__FILE__) . 'css/custom-slider-slider-public.css', array(), time(), 'all');
        wp_register_style($this->plugin_name . '-products', plugin_dir_url(__FILE__) . 'css/custom-slider-products-public.css', array(), time(), 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_register_script($this->plugin_name . '-slider', plugin_dir_url(__FILE__) . 'js/custom-slider-public-dist.js', array('jquery'), time(), true);
        wp_register_script($this->plugin_name . '-products', plugin_dir_url(__FILE__) . 'js/custom-slider-products-public-dist.js', array('jquery'), time(), true);

    }

    /**
     * Example of Shortcode processing function.
     *
     * Shortcode can take attributes like [custom-slider-shortcode attribute='123']
     * Shortcodes can be enclosing content [custom-slider-shortcode attribute='123']custom content[/custom-slider-shortcode].
     *
     * @see https://developer.wordpress.org/plugins/shortcodes/enclosing-shortcodes/
     *
     * @since    1.0.0
     * @param    array  $atts    ShortCode Attributes.
     * @param    mixed  $content ShortCode enclosed content.
     * @param    string $tag    The Shortcode tag.
     */
    public function custom_slider_shortcode_func($atts, $content = null)
    {
        // Encola el script donde este el Shortcode, evitando cargarlo en toda la página.
        wp_enqueue_style($this->plugin_name . '-slider');
        wp_enqueue_script($this->plugin_name. '-slider');

        // Combinar atributos predeterminados con los proporcionados por el usuario
        $atts = shortcode_atts(
            array(
                'attribute' => 123, // Atributo predeterminado
            ),
            $atts,
            $this->plugin_prefix . 'shortcode'
        );

        // Manejar el contenido del shortcode si es necesario
        if (!is_null($content) && !empty($content)) {
            $content = do_shortcode($content); // Procesar shortcodes anidados
            $content = sanitize_text_field($content); // Sanitizar el contenido
        }

        // Recuperar las imágenes del slider
        $slider_images = cmb2_get_option('custom_slider_options', 'custom_slider_repeat_group', []);

        // Recuperar los colores desde las opciones
        $arrow_color = cmb2_get_option('custom_slider_options', 'custom_slider_arrow_colorpicker', '#ffffff');
        $dots_color = cmb2_get_option('custom_slider_options', 'custom_slider_dots_colorpicker', '#ffffff');

        // Iniciar el buffer de salida
        ob_start();

        if (!empty($slider_images)) {
            require_once plugin_dir_path(__FILE__) . 'partials/custom-slider-public-display.php';
        } else {
            _e('No hay imágenes disponibles para el slider. Vaya al Panel de WordPress > Custom Slider > Añadir Imagen.', 'custom-slider');
        }

        // Capturar el contenido del buffer y limpiar
        $output = ob_get_clean();

        // Los shortcodes siempre deben devolver el contenido
        return $output;
    }

    /**
     * Shortcode para mostrar productos en oferta en un slider.
     *
     * @param array  $atts    Atributos proporcionados por el usuario en el shortcode.
     * @param string $content Contenido dentro del shortcode, si corresponde.
     *
     * @return string HTML generado por el shortcode.
     */
    public function custom_slider_products_shortcode_func($atts, $content = null)
    {
        // Encola el script de Swiper y los estilos necesarios
        wp_enqueue_script($this->plugin_name . '-products');
        wp_enqueue_style($this->plugin_name . '-products');

        // Combina atributos predeterminados con los proporcionados por el usuario
        $atts = shortcode_atts(
            array(
                'attribute' => 123, // Atributo predeterminado
            ),
            $atts,
            $this->plugin_prefix . 'shortcode'
        );

        // Obtener productos en oferta
        $products_on_sale = wc_get_product_ids_on_sale();

        // Si no hay productos en oferta, no proceder con el contenido
        if (empty($products_on_sale)) {
            return '<p>' . __('No hay productos en oferta en este momento.', 'custom-slider') . '</p>';
        }

        // Iniciar el buffer de salida
        ob_start();

        // Incluir el archivo con el HTML de la presentación de los productos
        require_once plugin_dir_path(__FILE__) . 'partials/custom-slider-products-public-display.php';

        // Capturar el contenido del buffer y limpiar
        $output = ob_get_clean();

        // Los shortcodes siempre deben devolver el contenido
        return $output;
    }

    public function sale_badge()
    {
        global $product;
        // Verificar si el producto tiene un precio rebajado
        if ($product->is_on_sale()) {
            echo '<span class="sale-badge">Oferta del Día</span>';
        }
    }

}
