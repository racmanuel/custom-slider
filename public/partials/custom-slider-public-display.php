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

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!-- Swiper -->
<div class="swiper mySwiper" style="--swiper-navigation-color: <?php echo esc_attr($arrow_color); ?>; --swiper-pagination-color: <?php echo esc_attr($dots_color); ?>;">
    <div class="swiper-wrapper">
        <?php foreach ($slider_images as $slide): ?>
            <?php if (!empty($slide['image'])): ?>
                <div class="swiper-slide">
                    <!-- Aseguramos que el atributo alt tenga un valor significativo para la accesibilidad -->
                    <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr('Imagen del slider: ' . basename($slide['image'])); ?>" loading="lazy"/>
                </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>

    <!-- Controles de navegación -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>
