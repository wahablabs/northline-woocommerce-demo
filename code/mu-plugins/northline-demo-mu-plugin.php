<?php
/**
 * Plugin Name: Northline Demo – Hardening, Header/Footer & Store Styles
 * Description: Concept demo helpers: security hardening, custom header/footer with CONCEPT DEMO bar, WooCommerce styling.
 */
/* ---------- Hardening ---------- */
add_action('send_headers', function () {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
    header_remove('X-Powered-By');
});
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', '__return_empty_array');
if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) { status_header(403); exit('XML-RPC disabled'); }
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');
add_filter('rest_endpoints', function ($e) {
    if (!is_user_logged_in()) { unset($e['/wp/v2/users'], $e['/wp/v2/users/(?P<id>[\d]+)']); }
    return $e;
});
/* ---------- Custom header / footer (replaces Hello's) ---------- */
add_filter('hello_elementor_header_footer', '__return_false');
add_filter('hello_elementor_page_title', function ($show) { return is_front_page() ? false : $show; });
add_action('wp_body_open', function () {
    $count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/';
    $cart = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '/';
    $cat = fn($s) => esc_url(home_url("/product-category/$s/"));
    ?>
<div class="nl-demo-bar">CONCEPT DEMO · Northline Apparel is a fictional brand built as a WooCommerce portfolio sample — no real orders or payments.</div>
<header class="nl-header"><div class="nl-wrap">
  <a class="nl-logo" href="<?php echo esc_url(home_url('/')); ?>">NORTHLINE<span>APPAREL</span></a>
  <nav class="nl-nav"><a href="<?php echo esc_url($shop); ?>">Shop All</a><a href="<?php echo $cat('tops'); ?>">Tops</a><a href="<?php echo $cat('outerwear'); ?>">Outerwear</a><a href="<?php echo $cat('accessories'); ?>">Accessories</a></nav>
  <a class="nl-cart" href="<?php echo esc_url($cart); ?>">Cart <b><?php echo (int)$count; ?></b></a>
</div></header>
<?php });
add_action('wp_footer', function () { ?>
<footer class="nl-footer"><div class="nl-wrap nl-fcols">
  <div><div class="nl-logo nl-logo-light">NORTHLINE<span>APPAREL</span></div><p>Everyday clothing made to last. A fictional brand for a WooCommerce concept demo.</p></div>
  <div><h4>Shop</h4><p>Tops<br>Outerwear<br>Bottoms<br>Accessories</p></div>
  <div><h4>Help</h4><p>Shipping (demo)<br>Returns (demo)<br>Size guide<br>Contact</p></div>
  <div><h4>Built with</h4><p>WordPress · WooCommerce<br>Elementor · Hello Elementor</p></div>
</div><div class="nl-copy">© 2026 Northline Apparel — CONCEPT DEMO · fictional brand · built by Abdul W.</div></footer>
<?php }, 1);
/* ---------- Styles ---------- */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('nl-fonts', 'https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap', [], null);
}, 5);
add_action('wp_head', function () { ?>
<style>
:root{--ink:#1E2230;--sand:#E9DFD0;--cream:#F7F3EC;--rust:#B4583F;--forest:#33553F;--muted:#6B6457}
html,body{overflow-x:hidden}
body{font-family:Inter,sans-serif;color:var(--ink);background:#fff}
h1,h2,h3,.nl-logo,.woocommerce-products-header__title,.product_title,.wp-block-woocommerce-cart h2{font-family:"DM Serif Display",serif;font-weight:400}
a{color:var(--ink)}
.nl-wrap{max-width:1240px;margin:0 auto;padding:0 24px}
.nl-demo-bar{background:var(--rust);color:#fff;text-align:center;font:600 13px/1.4 Inter;padding:9px 12px;letter-spacing:.3px}
.nl-header{background:#fff;border-bottom:1px solid #ECE6DC;position:sticky;top:0;z-index:50}
.nl-header .nl-wrap{display:flex;align-items:center;justify-content:space-between;height:74px}
.nl-logo{font-size:26px;letter-spacing:3px;text-decoration:none;color:var(--ink)}
.nl-logo span{font:600 11px Inter;letter-spacing:4px;margin-left:8px;color:var(--muted)}
.nl-nav a{margin:0 16px;text-decoration:none;font-weight:500;font-size:15px}
.nl-nav a:hover{color:var(--rust)}
.nl-cart{text-decoration:none;font-weight:600;border:1px solid var(--ink);padding:8px 16px;border-radius:999px}
.nl-cart b{background:var(--ink);color:#fff;border-radius:999px;padding:1px 8px;margin-left:6px;font-size:12px}
.nl-footer{background:var(--ink);color:#C9C3B8;margin-top:0}
.nl-fcols{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;padding-top:64px;padding-bottom:40px}
.nl-footer h4{color:#fff;font:600 14px Inter;letter-spacing:2px;text-transform:uppercase;margin:0 0 14px}
.nl-footer p{font-size:15px;line-height:1.9;margin:0}
.nl-logo-light{color:#fff;margin-bottom:12px}.nl-logo-light span{color:#9C9588}
.nl-copy{text-align:center;border-top:1px solid #333848;padding:18px;font-size:13px;color:#8E887C}
/* Woo archive + single */
body:not(.home) .site-main{max-width:1240px;margin:0 auto;padding:40px 24px 80px}
.woocommerce-products-header__title.page-title,.page-header .entry-title{font-size:48px;margin:10px 0 6px}
.woocommerce .woocommerce-breadcrumb{font-size:13px;color:var(--muted)}
.woocommerce ul.products{display:grid!important;grid-template-columns:repeat(4,1fr);gap:28px}
.woocommerce ul.products::before,.woocommerce ul.products::after{display:none!important}
.woocommerce ul.products li.product{width:auto!important;margin:0!important;float:none!important;text-align:left}
.woocommerce ul.products li.product img{border-radius:14px;background:var(--cream);margin-bottom:14px!important;aspect-ratio:1/1;object-fit:cover}
.woocommerce ul.products li.product .woocommerce-loop-product__title{font:600 16px Inter!important;padding:0!important;color:var(--ink)}
.woocommerce ul.products li.product .price{color:var(--ink)!important;font-weight:500;font-size:15px!important}
.woocommerce ul.products li.product .button,.woocommerce a.button,.woocommerce button.button,.single_add_to_cart_button{background:var(--ink)!important;color:#fff!important;border-radius:999px!important;padding:11px 22px!important;font:600 14px Inter!important;border:0}
.woocommerce ul.products li.product .button{margin-top:10px}
.woocommerce span.onsale{background:var(--rust);border-radius:999px;min-height:auto;line-height:1;padding:8px 12px;font-size:12px;top:12px;left:12px}
.woocommerce .woocommerce-result-count{color:var(--muted);font-size:14px}
.woocommerce .woocommerce-ordering select{border:1px solid #DDD5C8;border-radius:999px;padding:8px 14px}
.woocommerce div.product div.images img{border-radius:18px;background:var(--cream)}
.woocommerce div.product .product_title{font-size:46px;line-height:1.1;margin-bottom:10px}
.woocommerce div.product p.price,.woocommerce div.product span.price{color:var(--ink)!important;font-size:24px!important}
.woocommerce div.product form.cart .variations select{border:1px solid #DDD5C8;border-radius:10px;padding:10px 14px;min-width:220px;background:#fff}
.woocommerce div.product form.cart .variations th{padding-right:20px;vertical-align:middle}
.woocommerce .quantity .qty{border:1px solid #DDD5C8;border-radius:999px;padding:10px;width:70px}
.woocommerce div.product .woocommerce-tabs ul.tabs li{border-radius:999px 999px 0 0;background:var(--cream)}
.nl-trust{display:flex;gap:10px;flex-wrap:wrap;margin:18px 0 6px}.nl-trust span{background:var(--cream);border-radius:999px;padding:8px 14px;font-size:13px;font-weight:600}
.woocommerce-info,.woocommerce-message{border-top-color:var(--forest)}
/* block cart & checkout */
.wp-block-woocommerce-cart,.wp-block-woocommerce-checkout{max-width:1240px;margin:0 auto}
.page .entry-title{max-width:1240px;margin:0 auto 10px;font-size:48px}
.wc-block-components-button:not(.is-link),.wc-block-cart__submit-button,.wc-block-components-checkout-place-order-button{background:var(--ink)!important;color:#fff!important;border:0!important;border-radius:999px!important;font:600 16px Inter!important;text-decoration:none!important;min-height:54px}
.wc-block-components-button:not(.is-link):hover{background:#000!important}
.wc-block-components-text-input input,.wc-block-components-combobox input,.wc-blocks-components-select__select{border-radius:10px!important}
.wc-block-components-product-name{font-weight:600;color:var(--ink)!important}
.wc-block-cart-item__image img,.wc-block-components-order-summary-item__image img{border-radius:10px;background:var(--cream)}
button,[type=submit],[type=button]{border-color:var(--ink)}
@media(max-width:900px){.woocommerce ul.products{grid-template-columns:repeat(2,1fr);gap:16px}.nl-nav{display:none}.nl-fcols{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.woocommerce div.product .product_title{font-size:34px}.nl-logo{font-size:20px}.woocommerce-products-header__title.page-title{font-size:36px}}
</style>
<?php });
/* trust badges on single product */
add_action('woocommerce_single_product_summary', function () {
    echo '<div class="nl-trust"><span>🚚 Free shipping (demo)</span><span>↩️ 30-day returns (demo)</span><span>🧵 Concept demo product</span></div>';
}, 35);
add_filter('woocommerce_product_tabs', function ($t) { unset($t['reviews']); return $t; });
