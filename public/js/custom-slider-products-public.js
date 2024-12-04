// import Swiper bundle with all modules installed
import Swiper from "swiper/bundle";

// import styles bundle
import "swiper/css/bundle";

(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).on('load', function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practice to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  var swiper = new Swiper(".mySwiperProducts", {
    slidesPerView: 1, // Configuración por defecto para pantallas pequeñas
    spaceBetween: 10, // Espacio entre los productos
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      // Para pantallas pequeñas (≥ 480px)
      480: {
        slidesPerView: 2, // 2 productos por fila
        spaceBetween: 5, // Más espacio entre productos
      },
      // Para pantallas medianas (≥ 768px)
      768: {
        slidesPerView: 3, // 3 productos por fila
        spaceBetween: 10, // Aumenta el espacio
      },
      // Para pantallas grandes (≥ 1200px)
      1200: {
        slidesPerView: 4, // 4 productos por fila
        spaceBetween: 20, // Espaciado amplio para pantallas grandes
      },
    },
  });
})(jQuery);