<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://racmanuel.dev/
 * @since      1.0.0
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/public/partials
 */

// Asegurarse de que la variable $products_on_sale esté definida
if (!isset($products_on_sale) || empty($products_on_sale)) {
    return;
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="swiper mySwiperProducts">
    <div class="swiper-wrapper">
        <?php foreach ($products_on_sale as $product_id) {
            $wc_product = wc_get_product($product_id);

            $sale_price = $wc_product->get_sale_price();
            $regular_price = $wc_product->get_regular_price();
            $product_url = get_permalink($product_id);
            $product_title = esc_html($wc_product->get_name());
            $product_image_url = wp_get_attachment_url($wc_product->get_image_id());
        ?>
        <div class="swiper-slide">
            <div class="product-card">
                <!-- Imagen del Producto -->
                <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>" loading="lazy" class="product-image">

                <!-- Título del Producto -->
                <h3 class="product-title"><?php echo $product_title; ?></h3>

                <!-- Precio del Producto -->
                <p class="product-price">
                    <span class="sale-badge">Oferta del Día</span>
                    <!-- Precio regular tachado -->
                    <span class="regular-price"><?php echo wc_price($regular_price); ?></span>
                    <!-- Precio rebajado en rojo -->
                    <span class="sale-price"><?php echo wc_price($sale_price); ?></span>
                </p>

                <!-- Enlace al Producto -->
                <a href="<?php echo esc_url($product_url); ?>" class="product-link"><?php _e('Ver Producto', 'custom-slider');?></a>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Controles de Swiper -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>
