<?php
/**
 * Plugin Name: NexaTech Store Demo
 * Plugin URI:  https://github.com/
 * Description: Premium technology e-commerce built with WordPress, WooCommerce and custom Tailwind-inspired styling.
 * Version:     2.0.5
 * Author:      Simone Gonçalves
 * Text Domain: nexatech-store-demo
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

define('NEXATECH_DIR', plugin_dir_path(__FILE__));
define('NEXATECH_URL', plugin_dir_url(__FILE__));
define('NEXATECH_VERSION', '2.0.5');

/* ═══════════════════════════════════════════════
   FORCE WOOCOMMERCE LIVE MODE
═══════════════════════════════════════════════ */
add_filter('woocommerce_coming_soon_exclude', '__return_true', 9999);

function nexatech_force_woocommerce_live_mode()
{
    update_option('woocommerce_coming_soon', 'no');
    update_option('woocommerce_store_pages_only', 'no');
}
add_action('init', 'nexatech_force_woocommerce_live_mode', 1);


/* ═══════════════════════════════════════════════
   CUSTOM TEMPLATES: HOME + WOOCOMMERCE PAGES
═══════════════════════════════════════════════ */
function nexatech_use_plugin_templates($template)
{
    if (is_admin() || (function_exists('wp_doing_ajax') && wp_doing_ajax())) {
        return $template;
    }

    $front_template = NEXATECH_DIR . 'templates/nexatech-front-page.php';
    $woo_template = NEXATECH_DIR . 'templates/nexatech-woocommerce-page.php';

    if ((is_front_page() || is_page('nexatech-home')) && file_exists($front_template)) {
        return $front_template;
    }

    if (is_page('categorias') && file_exists($woo_template)) {
        return $woo_template;
    }

    $is_nexatech_woo_page = false;

    if (function_exists('is_woocommerce') && is_woocommerce()) {
        $is_nexatech_woo_page = true;
    }

    if (function_exists('is_cart') && is_cart()) {
        $is_nexatech_woo_page = true;
    }

    if (function_exists('is_checkout') && is_checkout()) {
        $is_nexatech_woo_page = true;
    }

    if (function_exists('is_account_page') && is_account_page()) {
        $is_nexatech_woo_page = true;
    }

    if ($is_nexatech_woo_page && file_exists($woo_template)) {
        return $woo_template;
    }

    return $template;
}
add_filter('template_include', 'nexatech_use_plugin_templates', 9999);
/* ═══════════════════════════════════════════════
   ASSETS
═══════════════════════════════════════════════ */
function nexatech_enqueue_assets()
{
    $css_file = NEXATECH_DIR . 'assets/dist/output.css';
    $js_file = NEXATECH_DIR . 'assets/js/nexatech-store.js';

    if (function_exists('WC')) {
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
    }

    if (file_exists($css_file)) {
        wp_enqueue_style(
            'nexatech-tailwind',
            NEXATECH_URL . 'assets/dist/output.css',
            array(),
            filemtime($css_file)
        );
    }

    if (file_exists($js_file)) {
        wp_enqueue_script(
            'nexatech-js',
            NEXATECH_URL . 'assets/js/nexatech-store.js',
            array(),
            filemtime($js_file),
            true
        );
    }

    $inline_css = '
        body.nexatech-fullpage { margin:0; background:#050816; color:#f8fafc; }
        .nexatech-fullpage a { text-decoration:none; }
        .nexatech-animate { opacity:0; transform:translateY(28px); transition:opacity .65s ease, transform .65s ease; }
        .nexatech-animate.nexatech-visible { opacity:1; transform:none; }
        #nexatech-header.nexatech-scrolled { background:rgba(5,8,22,.82); box-shadow:0 24px 70px rgba(0,0,0,.36); border-color:rgba(255,255,255,.14); }
        .nexatech-hidden { display:none !important; }
        .nexatech-block { display:block !important; }
        @keyframes ntPulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.2)} }
        .nexatech-cart-pulse { animation:ntPulse .55s ease; }
        .nexatech-btn-success { background:#10b981 !important; color:#04111a !important; }
        .woocommerce-page, .woocommerce { background:#050816; color:#f8fafc; }
        .woocommerce .woocommerce-message, .woocommerce .woocommerce-info, .woocommerce .woocommerce-error { background:#0b1020; color:#f8fafc; border-top-color:#06b6d4; border-radius:16px; box-shadow:0 18px 50px rgba(0,0,0,.25); }
        .woocommerce ul.products li.product { background:rgba(17,24,39,.86); border:1px solid rgba(255,255,255,.10); border-radius:24px; padding:14px; overflow:hidden; box-shadow:0 22px 60px rgba(0,0,0,.22); }
        .woocommerce ul.products li.product .woocommerce-loop-product__title { color:#f8fafc; font-weight:800; }
        .woocommerce ul.products li.product .price { color:#f5c76b; font-weight:900; }
        .woocommerce ul.products li.product .button, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button { background:linear-gradient(135deg,#06b6d4,#7c3aed); color:#fff; border-radius:999px; font-weight:800; border:0; }
        .woocommerce ul.products li.product .button:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover { filter:brightness(1.12); color:#fff; }
        .woocommerce-cart table.cart, .woocommerce table.shop_table, .woocommerce-checkout #payment, .woocommerce-cart .cart_totals, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register { background:rgba(11,16,32,.90); color:#f8fafc; border:1px solid rgba(255,255,255,.10); border-radius:22px; overflow:hidden; }
        .woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row select, .woocommerce .select2-container--default .select2-selection--single { background:#0f172a; color:#f8fafc; border:1px solid rgba(255,255,255,.14); border-radius:14px; min-height:44px; }
        .woocommerce #place_order { background:linear-gradient(135deg,#f5c76b,#f59e0b); color:#07111f; border-radius:999px; font-weight:900; }
        .woocommerce-MyAccount-navigation ul { list-style:none; padding:0; display:grid; gap:10px; }
        .woocommerce-MyAccount-navigation ul li a { color:#06b6d4; background:rgba(255,255,255,.06); padding:12px 14px; border-radius:14px; display:block; }
        .nt-category-menu { position:relative; }
        .nt-category-trigger { display:inline-flex; align-items:center; gap:8px; color:#f8fafc; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12); border-radius:999px; padding:10px 14px; font-weight:800; cursor:pointer; }
        .nt-category-trigger:hover { border-color:rgba(6,182,212,.55); color:#67e8f9; }
        .nt-category-dropdown { position:absolute; top:calc(100% + 14px); left:0; width:min(680px,90vw); display:none; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; padding:16px; border-radius:24px; background:rgba(11,16,32,.96); border:1px solid rgba(255,255,255,.12); box-shadow:0 30px 90px rgba(0,0,0,.38); backdrop-filter:blur(22px); z-index:999; }
        .nt-category-menu:hover .nt-category-dropdown, .nt-category-menu.nt-open .nt-category-dropdown { display:grid; }
        .nt-category-dropdown a, .nt-mobile-category-list a { display:flex; align-items:center; gap:12px; padding:12px; border-radius:16px; color:#f8fafc; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08); text-decoration:none; }
        .nt-category-dropdown a:hover, .nt-mobile-category-list a:hover { border-color:rgba(6,182,212,.45); background:rgba(6,182,212,.08); }
        .nt-category-dropdown svg, .nt-mobile-category-list svg { width:28px; height:28px; stroke:#06b6d4; fill:none; stroke-width:2; flex:0 0 auto; }
        .nt-category-dropdown strong, .nt-mobile-category-list strong { display:block; font-size:14px; }
        .nt-category-dropdown small, .nt-mobile-category-list small { display:block; color:#94a3b8; font-size:12px; margin-top:2px; }
        .nt-mobile-category-title { margin-top:10px; color:#67e8f9; font-size:12px; text-transform:uppercase; letter-spacing:.12em; font-weight:900; }
        .nt-mobile-category-list { display:grid; gap:8px; margin-top:8px; }
        .nt-product-photo { width:100%; height:100%; object-fit:cover; display:block; border-radius:inherit; }
        .nt-wc-product-visual { min-height:220px; border-radius:22px; display:flex; align-items:center; justify-content:center; background:radial-gradient(circle at 20% 10%,rgba(6,182,212,.25),transparent 38%),radial-gradient(circle at 80% 70%,rgba(124,58,237,.35),transparent 42%),#0b1020; overflow:hidden; }
        .nt-wc-product-visual .nt-product-visual { width:100%; height:220px; }
        .nt-wc-product-img { width:100%; aspect-ratio:1.25/1; object-fit:cover; border-radius:22px !important; margin-bottom:18px !important; }
        @media(max-width:900px){ .nt-category-menu { display:none; } .nt-category-dropdown{position:static;width:100%;} }
    ';

    if (wp_style_is('nexatech-tailwind', 'enqueued')) {
        wp_add_inline_style('nexatech-tailwind', $inline_css);
    }
}
add_action('wp_enqueue_scripts', 'nexatech_enqueue_assets');

/* ═══════════════════════════════════════════════
   CHECKOUT PAYMENT GATEWAY
═══════════════════════════════════════════════ */

function nexatech_checkout_notice()
{
    return;
}
add_action('woocommerce_before_checkout_form', 'nexatech_checkout_notice');

add_filter('woocommerce_order_button_text', function () {
    return 'Finalizar encomenda';
});

add_action('plugins_loaded', 'nexatech_register_payment_gateway', 20);

function nexatech_register_payment_gateway()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    if (!class_exists('NexaTech_Gateway_Pagamento')) {
        class NexaTech_Gateway_Pagamento extends WC_Payment_Gateway
        {
            public function __construct()
            {
                $this->id = 'nexatech_pagamento';
                $this->icon = '';
                $this->has_fields = true;
                $this->method_title = 'Método de pagamento';
                $this->method_description = 'Gateway de pagamento para MB WAY, cartão bancário e PayPal.';
                $this->supports = array('products');

                $this->init_form_fields();
                $this->init_settings();

                $this->enabled = $this->get_option('enabled', 'yes');
                $this->title = $this->get_option('title', 'Método de pagamento');
                $this->description = $this->get_option('description', 'Escolha a forma de pagamento pretendida.');

                add_action(
                    'woocommerce_update_options_payment_gateways_' . $this->id,
                    array($this, 'process_admin_options')
                );
            }

            public function init_form_fields()
            {
                $this->form_fields = array(
                    'enabled' => array(
                        'title' => 'Ativar',
                        'type' => 'checkbox',
                        'label' => 'Ativar método de pagamento',
                        'default' => 'yes',
                    ),
                    'title' => array(
                        'title' => 'Título',
                        'type' => 'text',
                        'default' => 'Método de pagamento',
                    ),
                    'description' => array(
                        'title' => 'Descrição',
                        'type' => 'textarea',
                        'default' => 'Escolha a forma de pagamento pretendida.',
                    ),
                );
            }

            public function payment_fields()
            {
                ?>
                <div class="nt-demo-payment-box">
                    <p><?php echo esc_html($this->description); ?></p>

                    <div class="nt-demo-method-grid">
                        <label class="nt-demo-method">
                            <input type="radio" name="nexatech_payment_method" value="mbway" checked>
                            <span>
                                <strong>MB WAY</strong>
                                <small>Pagamento rápido por telemóvel.</small>
                                <em>MB WAY</em>
                            </span>
                        </label>

                        <label class="nt-demo-method">
                            <input type="radio" name="nexatech_payment_method" value="card">
                            <span>
                                <strong>Cartão bancário</strong>
                                <small>Visa, Mastercard e cartões internacionais.</small>
                                <em>VISA · Mastercard</em>
                            </span>
                        </label>

                        <label class="nt-demo-method">
                            <input type="radio" name="nexatech_payment_method" value="paypal">
                            <span>
                                <strong>PayPal</strong>
                                <small>Pagamento através de conta digital.</small>
                                <em>PayPal</em>
                            </span>
                        </label>
                    </div>
                </div>
                <?php
            }

            public function validate_fields()
            {
                if (empty($_POST['nexatech_payment_method'])) {
                    wc_add_notice('Escolha um método de pagamento.', 'error');
                    return false;
                }

                return true;
            }

            public function process_payment($order_id)
            {
                $order = wc_get_order($order_id);

                if (!$order) {
                    return array('result' => 'failure');
                }

                $method = isset($_POST['nexatech_payment_method'])
                    ? sanitize_text_field(wp_unslash($_POST['nexatech_payment_method']))
                    : 'mbway';

                $labels = array(
                    'mbway' => 'MB WAY',
                    'card' => 'Cartão bancário',
                    'paypal' => 'PayPal',
                );

                $method_label = isset($labels[$method]) ? $labels[$method] : 'Método de pagamento';

                $order->update_meta_data('_nexatech_payment_method', $method_label);
                $order->update_status('processing', 'Encomenda criada com ' . $method_label . '.');
                $order->add_order_note('Método selecionado: ' . $method_label . '.');
                $order->save();

                if (WC()->cart) {
                    WC()->cart->empty_cart();
                }

                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order),
                );
            }
        }
    }

    add_filter('woocommerce_payment_gateways', function ($gateways) {
        $gateways[] = 'NexaTech_Gateway_Pagamento';
        return $gateways;
    });
}

function nexatech_enable_payment_gateway()
{
    update_option('woocommerce_nexatech_pagamento_settings', array(
        'enabled' => 'yes',
        'title' => 'Método de pagamento',
        'description' => 'Escolha a forma de pagamento pretendida.',
    ));

    $cod_settings = get_option('woocommerce_cod_settings', array());
    $cod_settings['enabled'] = 'no';
    update_option('woocommerce_cod_settings', $cod_settings);
}
add_action('init', 'nexatech_enable_payment_gateway', 5);

function nexatech_only_payment_gateway($gateways)
{
    if (is_admin() && !wp_doing_ajax()) {
        return $gateways;
    }

    if (isset($gateways['nexatech_pagamento'])) {
        return array('nexatech_pagamento' => $gateways['nexatech_pagamento']);
    }

    return $gateways;
}
add_filter('woocommerce_available_payment_gateways', 'nexatech_only_payment_gateway', 9999);

function nexatech_checkout_payment_styles()
{
    if (!function_exists('is_checkout') || !is_checkout()) {
        return;
    }
    ?>
    <style>
        .woocommerce-checkout #payment {
            background: rgba(15, 23, 42, .88) !important;
            border: 1px solid rgba(255, 255, 255, .10) !important;
            border-radius: 24px !important;
            padding: 22px !important;
            color: #F8FAFC !important;
        }

        .woocommerce-checkout #payment ul.payment_methods {
            padding: 0 !important;
            margin: 0 !important;
            border: 0 !important;
        }

        .woocommerce-checkout #payment ul.payment_methods li.wc_payment_method {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            color: #F8FAFC !important;
        }

        .woocommerce-checkout #payment ul.payment_methods li.wc_payment_method>input {
            display: none !important;
        }

        .woocommerce-checkout #payment ul.payment_methods li.wc_payment_method>label {
            display: block;
            margin: 0 0 16px;
            color: #F8FAFC !important;
            font-size: 22px;
            font-weight: 900;
        }

        .woocommerce-checkout #payment div.payment_box {
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            color: #CBD5E1 !important;
        }

        .woocommerce-checkout #payment div.payment_box::before {
            display: none !important;
        }

        .nt-demo-payment-box {
            margin-top: 8px;
        }

        .nt-demo-payment-box>p {
            margin: 0 0 16px;
            color: #CBD5E1;
            font-size: 15px;
            line-height: 1.6;
        }

        .nt-demo-method-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin: 16px 0;
        }

        .nt-demo-method {
            position: relative;
            cursor: pointer;
        }

        .nt-demo-method input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .nt-demo-method span {
            display: block;
            min-height: 128px;
            padding: 20px;
            border-radius: 20px;
            background: rgba(2, 6, 23, .62);
            border: 1px solid rgba(255, 255, 255, .12);
            transition: .25s ease;
        }

        .nt-demo-method strong {
            display: block;
            color: #F8FAFC;
            font-size: 17px;
            margin-bottom: 8px;
        }

        .nt-demo-method small {
            display: block;
            color: #94A3B8;
            font-size: 13px;
            line-height: 1.45;
        }

        .nt-demo-method em {
            display: inline-flex;
            margin-top: 14px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(6, 182, 212, .12);
            border: 1px solid rgba(6, 182, 212, .28);
            color: #67E8F9;
            font-style: normal;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .nt-demo-method:hover span {
            border-color: rgba(6, 182, 212, .55);
            box-shadow: 0 18px 50px rgba(6, 182, 212, .10);
            transform: translateY(-2px);
        }

        .nt-demo-method input:checked+span {
            border-color: #06B6D4;
            background:
                linear-gradient(135deg, rgba(6, 182, 212, .18), rgba(124, 58, 237, .16)),
                rgba(2, 6, 23, .80);
            box-shadow: 0 0 0 1px rgba(6, 182, 212, .35), 0 22px 60px rgba(6, 182, 212, .14);
        }

        .woocommerce #place_order {
            width: 100%;
            margin-top: 22px !important;
            background: linear-gradient(135deg, #F5C76B, #06B6D4) !important;
            color: #020617 !important;
            border-radius: 999px !important;
            font-weight: 900 !important;
            font-size: 17px !important;
            padding: 17px 26px !important;
            box-shadow: 0 18px 50px rgba(6, 182, 212, .20);
        }

        .woocommerce #place_order:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
        }

        @media (max-width: 820px) {
            .nt-demo-method-grid {
                grid-template-columns: 1fr;
            }

            .nt-demo-method span {
                min-height: auto;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'nexatech_checkout_payment_styles', 50);

/* ═══════════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════════ */
function nexatech_get_page_url($wc_page)
{
    if (!function_exists('wc_get_page_id')) {
        return home_url('/');
    }

    $page_id = wc_get_page_id($wc_page);
    return $page_id > 0 ? get_permalink($page_id) : home_url('/');
}

function nexatech_category_icon($name)
{
    $icons = array(
        'Laptops' => '<svg viewBox="0 0 64 64" aria-hidden="true"><rect x="10" y="14" width="44" height="30" rx="4"></rect><path d="M6 50h52l-5 6H11z"></path><path d="M20 22h24"></path></svg>',
        'Smartphones' => '<svg viewBox="0 0 64 64" aria-hidden="true"><rect x="20" y="6" width="24" height="52" rx="6"></rect><circle cx="32" cy="50" r="2"></circle><path d="M27 13h10"></path></svg>',
        'Acessórios' => '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M20 34c0-9 5-16 12-16s12 7 12 16"></path><path d="M18 34h8v14h-8zM38 34h8v14h-8z"></path><path d="M32 18V9"></path></svg>',
        'Gaming' => '<svg viewBox="0 0 64 64" aria-hidden="true"><rect x="10" y="24" width="44" height="22" rx="10"></rect><path d="M24 35h-8M20 31v8M42 32h.1M48 38h.1"></path></svg>',
        'Smart Home' => '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M10 30 32 12l22 18"></path><path d="M16 28v24h32V28"></path><path d="M26 52V38h12v14"></path></svg>',
    );

    return isset($icons[$name]) ? $icons[$name] : '<svg viewBox="0 0 64 64" aria-hidden="true"><circle cx="32" cy="32" r="20"></circle><path d="M22 32h20M32 22v20"></path></svg>';
}

function nexatech_device_type_from_category($cat_name)
{
    $map = array(
        'Laptops' => 'laptop',
        'Smartphones' => 'phone',
        'Acessórios' => 'accessory',
        'Gaming' => 'gaming',
        'Smart Home' => 'home',
    );

    return isset($map[$cat_name]) ? $map[$cat_name] : 'device';
}

function nexatech_product_visual($cat_name)
{
    $type = nexatech_device_type_from_category($cat_name);

    return '<div class="nt-product-visual nt-product-visual-' . esc_attr($type) . '">
        <span class="nt-product-glow"></span>
        <span class="nt-device-shape"></span>
        <span class="nt-device-line nt-line-a"></span>
        <span class="nt-device-line nt-line-b"></span>
    </div>';
}

function nexatech_product_image_url($product)
{
    if (!$product instanceof WC_Product) {
        return '';
    }

    $sku = strtolower($product->get_sku());
    $slug = sanitize_title($product->get_name());
    $candidates = array();

    foreach (array('webp', 'jpg', 'jpeg', 'png') as $ext) {
        if ($sku) {
            $candidates[] = 'assets/img/products/' . $sku . '.' . $ext;
        }
        $candidates[] = 'assets/img/products/' . $slug . '.' . $ext;
    }

    foreach ($candidates as $relative_path) {
        if (file_exists(NEXATECH_DIR . $relative_path)) {
            return NEXATECH_URL . $relative_path;
        }
    }

    return '';
}

function nexatech_product_media_html($product, $cat_name)
{
    $image_url = nexatech_product_image_url($product);

    if ($image_url) {
        return '<img class="nt-product-photo" src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '" loading="lazy">';
    }

    return nexatech_product_visual($cat_name);
}

function nexatech_replace_woocommerce_product_image($image, $product, $size, $attr, $placeholder)
{
    if (!$product instanceof WC_Product || has_post_thumbnail($product->get_id())) {
        return $image;
    }

    $terms = get_the_terms($product->get_id(), 'product_cat');
    $cat_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'Tecnologia';
    $image_url = nexatech_product_image_url($product);

    if ($image_url) {
        return '<img class="nt-wc-product-img" src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '" loading="lazy">';
    }

    return '<div class="nt-wc-product-visual">' . nexatech_product_visual($cat_name) . '</div>';
}
add_filter('woocommerce_product_get_image', 'nexatech_replace_woocommerce_product_image', 20, 5);

function nexatech_get_product_categories()
{
    if (!taxonomy_exists('product_cat')) {
        return array();
    }

    $cats = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'exclude' => get_option('default_product_cat'),
    ));

    return (!empty($cats) && !is_wp_error($cats)) ? $cats : array();
}

function nexatech_render_category_dropdown($cats = array())
{
    ob_start();
    ?>
    <div class="nt-category-menu">
        <button class="nt-category-trigger" type="button" aria-label="Abrir categorias">
            <span style="margin-right:8px;">☰</span> Categorias
        </button>

        <div class="nt-category-dropdown">
            <?php if (!empty($cats) && !is_wp_error($cats)): ?>
                <?php foreach ($cats as $cat): ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>">
                        <span style="display:flex;align-items:center;gap:10px;">
                            <span><?php echo function_exists('nexatech_category_icon') ? nexatech_category_icon($cat->name) : '✦'; ?></span>
                            <span>
                                <strong><?php echo esc_html($cat->name); ?></strong><br>
                                <small><?php echo absint($cat->count); ?> produto(s)</small>
                            </span>
                        </span>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="<?php echo esc_url(home_url('/categorias/')); ?>">
                    <span style="display:flex;align-items:center;gap:10px;">
                        <span>✦</span>
                        <span>
                            <strong>Ver categorias</strong><br>
                            <small>Explorar produtos</small>
                        </span>
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
function nexatech_render_mobile_categories($cats)
{
    if (empty($cats)) {
        return '';
    }

    ob_start();
    ?>
    <div class="nt-mobile-category-title">Categorias</div>
    <div class="nt-mobile-category-list">
        <?php foreach ($cats as $cat): ?>
            <a href="<?php echo esc_url(get_term_link($cat)); ?>">
                <span><?php echo nexatech_category_icon($cat->name); ?></span>
                <span><strong><?php echo esc_html($cat->name); ?></strong><small><?php echo absint($cat->count); ?>
                        produto(s)</small></span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

function nexatech_product_card($product, $compact = false)
{
    if (!$product instanceof WC_Product) {
        return '';
    }

    $terms = get_the_terms($product->get_id(), 'product_cat');
    $cat_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'Tecnologia';
    $badge = '';

    if ($product->is_on_sale()) {
        $badge = 'Promoção';
    } elseif ($product->is_featured()) {
        $badge = 'Mais vendido';
    } elseif (time() - get_post_time('U', true, $product->get_id()) < 60 * 60 * 24 * 45) {
        $badge = 'Novo';
    }

    ob_start();
    ?>
    <article class="nt-product-card nexatech-animate <?php echo $compact ? 'nt-product-card-compact' : ''; ?>">
        <?php if ($badge): ?><span class="nt-product-badge"><?php echo esc_html($badge); ?></span><?php endif; ?>
        <a class="nt-product-media" href="<?php echo esc_url($product->get_permalink()); ?>"
            aria-label="<?php echo esc_attr($product->get_name()); ?>">
            <?php echo nexatech_product_media_html($product, $cat_name); ?>
        </a>
        <div class="nt-product-content">
            <span class="nt-product-category"><?php echo esc_html($cat_name); ?></span>
            <h3><a
                    href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
            </h3>
            <?php if (!$compact): ?>
                <p><?php echo esc_html(wp_trim_words($product->get_short_description(), 11)); ?></p>
            <?php endif; ?>
            <div class="nt-rating" aria-label="Avaliação visual">★★★★★ <small>4.9</small></div>
            <div class="nt-product-bottom">
                <strong class="nt-price"><?php echo wp_kses_post($product->get_price_html()); ?></strong>
                <a class="nt-add-btn nexatech-add-to-cart add_to_cart_button ajax_add_to_cart product_type_simple"
                    href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                    data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                    data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" data-quantity="1"
                    aria-label="<?php echo esc_attr('Adicionar ' . $product->get_name() . ' ao carrinho'); ?>"
                    rel="nofollow">Adicionar</a>
            </div>
        </div>
    </article>
    <?php
    return ob_get_clean();
}

function nexatech_section_heading($eyebrow, $title, $text = '')
{
    ob_start();
    ?>
    <div class="nt-section-head nexatech-animate">
        <span><?php echo esc_html($eyebrow); ?></span>
        <h2><?php echo esc_html($title); ?></h2>
        <?php if ($text): ?>
            <p><?php echo esc_html($text); ?></p><?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/* ═══════════════════════════════════════════════
   HOMEPAGE SHORTCODE
═══════════════════════════════════════════════ */
function nexatech_homepage_shortcode()
{
    if (!function_exists('WC')) {
        return '<main class="nt-root"><div class="nt-container" style="padding:80px 24px;"><h1>NexaTech Store</h1><p>Active o WooCommerce para visualizar a loja.</p></div></main>';
    }

    $shop_url = esc_url(nexatech_get_page_url('shop'));
    $cart_url = esc_url(wc_get_cart_url());
    $checkout_url = esc_url(wc_get_checkout_url());
    $account_url = esc_url(nexatech_get_page_url('myaccount'));
    $home_url = esc_url(home_url('/'));
    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

    $cats = nexatech_get_product_categories();

    $featured_products = wc_get_products(array(
        'limit' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'status' => 'publish',
    ));

    $bestseller_products = wc_get_products(array(
        'limit' => 6,
        'featured' => true,
        'status' => 'publish',
    ));

    /*
     * Se existirem menos de 6 produtos marcados como “Mais vendido”,
     * completa o slider com outros produtos recentes, sem repetir.
     */
    $used_product_ids = array();

    foreach ($bestseller_products as $product) {
        if ($product instanceof WC_Product) {
            $used_product_ids[] = $product->get_id();
        }
    }

    foreach ($featured_products as $product) {
        if (count($bestseller_products) >= 6) {
            break;
        }

        if (
            $product instanceof WC_Product &&
            !in_array($product->get_id(), $used_product_ids, true)
        ) {
            $bestseller_products[] = $product;
            $used_product_ids[] = $product->get_id();
        }
    }

    $bestseller_products = array_slice($bestseller_products, 0, 6);

    $deal_product = !empty($featured_products) ? $featured_products[0] : null;

    ob_start();
    ?>
    <main id="nexatech-root" class="nt-root">
        <div class="nt-aurora nt-aurora-one"></div>
        <div class="nt-aurora nt-aurora-two"></div>
        <div class="nt-grid-bg"></div>
        <div class="nt-noise"></div>

        <div class="nt-topbar">
            <div class="nt-container nt-topbar-inner">
                <span>Premium Tech Marketplace</span>
                <span>Envio rápido</span>
                <span>Pagamentos seguros</span>
                <span>Suporte especializado</span>
            </div>
        </div>

        <header id="nexatech-header" class="nt-header">
            <div class="nt-container nt-header-inner">
                <a class="nt-logo" href="<?php echo $home_url; ?>" aria-label="NexaTech Store">
                    <span class="nt-logo-mark">✦</span>
                    <span>Nexa<span>Tech</span> Store</span>
                </a>

                <nav class="nt-desktop-nav" aria-label="Menu principal">
                    <a href="<?php echo $home_url; ?>">Home</a>
                    <?php echo nexatech_render_category_dropdown($cats); ?>
                    <a href="<?php echo $cart_url; ?>">Carrinho</a>
                    <a href="<?php echo $checkout_url; ?>">Checkout</a>
                    <a href="<?php echo $account_url; ?>">Conta</a>
                </nav>

                <div class="nt-header-actions">
                    <a class="nt-cart-pill" href="<?php echo $cart_url; ?>">
                        <span>🛒</span> Carrinho <b class="woocommerce-cart-count"><?php echo esc_html($cart_count); ?></b>
                    </a>
                    <button id="nexatech-mobile-toggle" class="nt-menu-toggle" aria-expanded="false"
                        aria-label="Abrir menu">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>

            <nav id="nexatech-mobile-menu" class="nt-mobile-menu nexatech-hidden" aria-label="Menu mobile">
                <a href="<?php echo $home_url; ?>">Home</a>
                <a href="<?php echo $shop_url; ?>">Loja</a>
                <?php echo nexatech_render_mobile_categories($cats); ?>
                <a href="<?php echo $cart_url; ?>">Carrinho</a>
                <a href="<?php echo $checkout_url; ?>">Checkout</a>
                <a href="<?php echo $account_url; ?>">Minha Conta</a>
            </nav>
        </header>

        <section class="nt-hero">
            <div class="nt-container nt-hero-grid">
                <div class="nt-hero-copy nexatech-animate">
                    <span class="nt-eyebrow">Premium Tech Marketplace</span>
                    <h1>Smart tech for a smarter setup.</h1>
                    <p class="nt-hero-subtitle">Tecnologia premium para produtividade, gaming, smart home e setups digitais
                        modernos.</p>
                    <p class="nt-hero-text">Explora uma seleção curada de laptops, smartphones, acessórios e periféricos
                        pensados para uma experiência de compra rápida, elegante e responsiva.</p>

                    <div class="nt-hero-actions">
                        <a class="nt-btn nt-btn-primary" href="<?php echo $shop_url; ?>">Comprar tecnologia</a>
                        <a class="nt-btn nt-btn-ghost" href="<?php echo esc_url(home_url('/categorias/')); ?>">Ver
                            categorias</a>
                    </div>

                    <div class="nt-trust-row">
                        <span>+16 produtos</span>
                        <span>5 categorias</span>
                        <span>Entrega rápida</span>
                        <span>Garantia tech</span>
                    </div>
                </div>

                <div class="nt-hero-stage nexatech-animate" aria-hidden="true">
                    <div class="nt-orbit nt-orbit-a"></div>
                    <div class="nt-orbit nt-orbit-b"></div>
                    <div class="nt-stage-card nt-stage-laptop">
                        <span class="nt-stage-label">NovaBook Pro</span>
                        <span class="nt-laptop-screen"></span>
                        <span class="nt-laptop-base"></span>
                    </div>
                    <div class="nt-stage-card nt-stage-phone">
                        <span class="nt-phone-screen"></span>
                    </div>
                    <div class="nt-stage-card nt-stage-pods">
                        <span></span><span></span>
                    </div>
                    <div class="nt-stage-card nt-stage-chip">
                        <strong>4K</strong><small>Ultra setup</small>
                    </div>
                    <div class="nt-stage-price">desde <strong>39€</strong></div>
                </div>
            </div>
        </section>

        <section id="nt-categories" class="nt-section nt-categories-section">
            <div class="nt-container">
                <?php echo nexatech_section_heading('Shop by category', 'Categorias em destaque', 'Escolhe o teu próximo upgrade por ambiente, dispositivo ou tipo de setup.'); ?>

                <div class="nt-category-grid">
                    <?php if (!empty($cats) && !is_wp_error($cats)): ?>
                        <?php foreach ($cats as $cat): ?>
                            <a class="nt-category-card nexatech-animate" href="<?php echo esc_url(get_term_link($cat)); ?>">
                                <span class="nt-category-icon"><?php echo nexatech_category_icon($cat->name); ?></span>
                                <strong><?php echo esc_html($cat->name); ?></strong>
                                <small><?php echo absint($cat->count); ?> produtos</small>
                                <em>Explorar →</em>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="nt-section nt-products-section">
            <div class="nt-container">
                <?php echo nexatech_section_heading('Curated selection', 'Produtos em destaque', 'Uma montra moderna com cards de produto, preços, categorias, badges e carrinho WooCommerce.'); ?>

                <div class="nt-products-grid">
                    <?php foreach ($featured_products as $product): ?>
                        <?php echo nexatech_product_card($product); ?>
                    <?php endforeach; ?>
                </div>

                <div class="nt-center-action nexatech-animate">
                    <a class="nt-btn nt-btn-ghost" href="<?php echo $shop_url; ?>">Ver todos os produtos</a>
                </div>
            </div>
        </section>

        <section class="nt-section nt-deal-section">
            <div class="nt-container">
                <div class="nt-deal-card nexatech-animate">
                    <div class="nt-deal-copy">
                        <span class="nt-eyebrow">Deal of the Week</span>
                        <h2>Upgrade completo para o teu setup.</h2>
                        <p>Combina performance, design e acessórios inteligentes numa experiência de compra WooCommerce
                            fluida.</p>
                        <div class="nt-deal-actions">
                            <a class="nt-btn nt-btn-gold" href="<?php echo $shop_url; ?>">Explorar ofertas</a>
                            <?php if ($deal_product): ?>
                                <span class="nt-deal-price"><?php echo wp_kses_post($deal_product->get_price_html()); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="nt-deal-visual" aria-hidden="true">
                        <span class="nt-deal-monitor"></span>
                        <span class="nt-deal-keyboard"></span>
                        <span class="nt-deal-phone"></span>
                    </div>
                </div>
            </div>
        </section>

        <section class="nt-section nt-slider-section">
            <div class="nt-container">
                <?php echo nexatech_section_heading('Trending now', 'Mais vendidos', 'Produtos em destaque com navegação horizontal e visual de loja premium.'); ?>

                <div class="nt-slider-wrap nexatech-animate">
                    <button class="nt-slider-btn nt-slider-prev" type="button" aria-label="Produtos anteriores">‹</button>
                    <div class="nt-product-slider" data-nexatech-slider>
                        <?php foreach ($bestseller_products as $product): ?>
                            <div class="nt-slide-item"><?php echo nexatech_product_card($product, true); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <button class="nt-slider-btn nt-slider-next" type="button" aria-label="Próximos produtos">›</button>
                </div>
            </div>
        </section>

        <section class="nt-section nt-benefits-section">
            <div class="nt-container">
                <?php echo nexatech_section_heading('Why NexaTech', 'Uma experiência pensada para comprar melhor', 'Do primeiro clique ao checkout, tudo foi desenhado para parecer uma loja real, rápida e confiável.'); ?>
                <?php
                $benefits = array(
                    array('icon' => '⚡', 'title' => 'Performance premium', 'desc' => 'Layout leve, responsivo e com transições suaves.'),
                    array('icon' => '🛒', 'title' => 'Carrinho WooCommerce', 'desc' => 'Fluxo real de loja com produtos, carrinho e checkout.'),
                    array('icon' => '🔐', 'title' => 'Compra segura', 'desc' => 'Checkout preparado para modo demonstrativo e sem pagamentos reais.'),
                    array('icon' => '📦', 'title' => 'Envio rápido', 'desc' => 'Benefícios comerciais organizados como loja profissional.'),
                    array('icon' => '💬', 'title' => 'Suporte especializado', 'desc' => 'Copy e UX com foco em confiança e conversão.'),
                    array('icon' => '📱', 'title' => 'Mobile first', 'desc' => 'Design adaptado para desktop, tablet e telemóvel.'),
                );
                ?>
                <div class="nt-benefits-grid">
                    <?php foreach ($benefits as $benefit): ?>
                        <article class="nt-benefit-card nexatech-animate">
                            <span><?php echo esc_html($benefit['icon']); ?></span>
                            <h3><?php echo esc_html($benefit['title']); ?></h3>
                            <p><?php echo esc_html($benefit['desc']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="nt-section nt-newsletter-section">
            <div class="nt-container">
                <div class="nt-newsletter nexatech-animate">
                    <div>
                        <span class="nt-eyebrow">NexaTech Club</span>
                        <h2>Recebe novidades de tecnologia no teu setup.</h2>
                        <p>Newsletter visual para simular uma experiência comercial completa.</p>
                    </div>
                    <form class="nt-newsletter-form" onsubmit="return false;">
                        <input type="email" placeholder="o-teu-email@email.com" aria-label="Email">
                        <button type="submit">Subscrever</button>
                    </form>
                </div>
            </div>
        </section>

        <footer class="nt-footer">
            <div class="nt-container nt-footer-grid">
                <div>
                    <a class="nt-logo" href="<?php echo $home_url; ?>"><span
                            class="nt-logo-mark">✦</span><span>Nexa<span>Tech</span> Store</span></a>
                    <p>Marketplace de tecnologia premium para setups modernos, gaming, produtividade e smart home.</p>
                    <small>Projeto demonstrativo. Nenhum produto ou pagamento é real.</small>
                </div>
                <div>
                    <strong>Loja</strong>
                    <a href="<?php echo $shop_url; ?>">Produtos</a>
                    <a href="<?php echo $cart_url; ?>">Carrinho</a>
                </div>
                <div>
                    <strong>Conta</strong>
                    <a href="<?php echo $checkout_url; ?>">Checkout</a>
                    <a href="<?php echo $account_url; ?>">Minha Conta</a>
                    <a href="<?php echo $home_url; ?>">Home</a>
                </div>
            </div>
        </footer>
    </main>
    <?php
    return ob_get_clean();
}
add_shortcode('nexatech_homepage', 'nexatech_homepage_shortcode');


/* ═══════════════════════════════════════════════
   CATEGORIES PAGE SHORTCODE
═══════════════════════════════════════════════ */
function nexatech_categories_shortcode()
{
    if (!taxonomy_exists('product_cat')) {
        return '<p>Categorias indisponíveis.</p>';
    }

    $cats = nexatech_get_product_categories();

    ob_start();
    ?>
    <section class="nt-categories-page">
        <?php echo nexatech_section_heading('Shop by category', 'Categorias NexaTech', 'Explora produtos por tipo de setup, dispositivo ou ambiente digital.'); ?>

        <div class="nt-category-grid nt-category-grid-large">
            <?php if (!empty($cats) && !is_wp_error($cats)): ?>
                <?php foreach ($cats as $cat): ?>
                    <a class="nt-category-card nexatech-animate" href="<?php echo esc_url(get_term_link($cat)); ?>">
                        <span class="nt-category-icon"><?php echo nexatech_category_icon($cat->name); ?></span>
                        <strong><?php echo esc_html($cat->name); ?></strong>
                        <small><?php echo absint($cat->count); ?> produto(s)</small>
                        <p><?php echo esc_html($cat->description ?: 'Tecnologia selecionada para setups modernos.'); ?></p>
                        <em>Ver produtos →</em>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('nexatech_categories', 'nexatech_categories_shortcode');

/* ═══════════════════════════════════════════════
   SETUP
═══════════════════════════════════════════════ */
function nexatech_get_page_by_path_or_title($path, $title)
{
    $page = get_page_by_path($path);
    if ($page) {
        return $page;
    }

    $query = get_posts(array(
        'post_type' => 'page',
        'post_status' => array('publish', 'draft', 'private'),
        'title' => $title,
        'posts_per_page' => 1,
    ));

    return !empty($query) ? $query[0] : null;
}

function nexatech_find_product_by_title($title)
{
    $query = get_posts(array(
        'post_type' => 'product',
        'post_status' => array('publish', 'draft', 'private'),
        'title' => $title,
        'posts_per_page' => 1,
        'fields' => 'ids',
    ));

    return !empty($query) ? absint($query[0]) : 0;
}

function nexatech_run_setup()
{
    if (get_option('nexatech_setup_version') === NEXATECH_VERSION) {
        return;
    }

    $home = nexatech_get_page_by_path_or_title('nexatech-home', 'Home');

    if (!$home) {
        $home_id = wp_insert_post(array(
            'post_title' => 'Home',
            'post_name' => 'nexatech-home',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => '[nexatech_homepage]',
        ));
    } else {
        $home_id = $home->ID;
        wp_update_post(array(
            'ID' => $home_id,
            'post_title' => 'Home',
            'post_name' => 'nexatech-home',
            'post_status' => 'publish',
            'post_content' => '[nexatech_homepage]',
        ));
    }

    if (!is_wp_error($home_id)) {
        update_option('page_on_front', $home_id);
        update_option('show_on_front', 'page');
    }

    $categories_page = nexatech_get_page_by_path_or_title('categorias', 'Categorias');

    if (!$categories_page) {
        wp_insert_post(array(
            'post_title' => 'Categorias',
            'post_name' => 'categorias',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => '[nexatech_categories]',
        ));
    } else {
        wp_update_post(array(
            'ID' => $categories_page->ID,
            'post_title' => 'Categorias',
            'post_name' => 'categorias',
            'post_status' => 'publish',
            'post_content' => '[nexatech_categories]',
        ));
    }

    if (function_exists('wc_create_page')) {
        $wc_pages = array(
            'woocommerce_shop_page_id' => array('shop', 'Loja', ''),
            'woocommerce_cart_page_id' => array('cart', 'Carrinho', '[woocommerce_cart]'),
            'woocommerce_checkout_page_id' => array('checkout', 'Checkout', '[woocommerce_checkout]'),
            'woocommerce_myaccount_page_id' => array('myaccount', 'Minha Conta', '[woocommerce_my_account]'),
        );

        foreach ($wc_pages as $option => $page_data) {
            $existing_id = absint(get_option($option));
            if (!$existing_id || !get_post($existing_id)) {
                $new_id = wc_create_page(sanitize_title($page_data[0]), $option, $page_data[1], $page_data[2]);
                if ($new_id) {
                    update_option($option, $new_id);
                }
            }
        }
    }

    if (class_exists('WooCommerce')) {
        update_option('woocommerce_currency', 'EUR');
        update_option('woocommerce_nexatech_pagamento_settings', array(
            'enabled' => 'yes',
            'title' => 'Método de pagamento',
            'description' => 'Escolha a forma de pagamento pretendida.',
        ));

        $cod_settings = get_option('woocommerce_cod_settings', array());
        $cod_settings['enabled'] = 'no';
        update_option('woocommerce_cod_settings', $cod_settings);
    }

    if (taxonomy_exists('product_cat')) {
        $categories = array(
            'Laptops' => 'Computadores portáteis de alto desempenho para produtividade e criatividade.',
            'Smartphones' => 'Smartphones modernos com design premium e autonomia avançada.',
            'Acessórios' => 'Acessórios essenciais para setups profissionais e mobile.',
            'Gaming' => 'Periféricos gaming para performance, conforto e precisão.',
            'Smart Home' => 'Dispositivos inteligentes para uma casa conectada.',
        );

        foreach ($categories as $name => $desc) {
            if (!term_exists($name, 'product_cat')) {
                wp_insert_term($name, 'product_cat', array('description' => $desc));
            }
        }
    }

    if (class_exists('WC_Product_Simple') && taxonomy_exists('product_cat')) {
        $products = array(
            array('sku' => 'NT-001', 'title' => 'NovaBook Air 14', 'cat' => 'Laptops', 'price' => 899, 'sale' => 799, 'badge' => 'Promoção', 'desc' => 'Laptop ultraleve para produtividade diária.', 'full' => 'O NovaBook Air 14 combina leveza, autonomia e um design minimalista para trabalho, estudo e mobilidade.'),
            array('sku' => 'NT-002', 'title' => 'NovaBook Pro 16', 'cat' => 'Laptops', 'price' => 1499, 'sale' => 0, 'badge' => 'Novo', 'desc' => 'Performance premium para trabalho criativo.', 'full' => 'O NovaBook Pro 16 entrega potência para multitasking, edição, programação e produtividade avançada.'),
            array('sku' => 'NT-003', 'title' => 'QuantumPhone X', 'cat' => 'Smartphones', 'price' => 999, 'sale' => 899, 'badge' => 'Mais vendido', 'desc' => 'Smartphone premium com câmara avançada.', 'full' => 'O QuantumPhone X combina ecrã fluido, fotografia inteligente e carregamento rápido num design elegante.'),
            array('sku' => 'NT-004', 'title' => 'QuantumPhone Lite', 'cat' => 'Smartphones', 'price' => 499, 'sale' => 0, 'badge' => 'Novo', 'desc' => 'Smartphone acessível com excelente autonomia.', 'full' => 'Uma opção equilibrada para quem procura autonomia, design moderno e desempenho diário.'),
            array('sku' => 'NT-005', 'title' => 'PulsePods Pro', 'cat' => 'Acessórios', 'price' => 159, 'sale' => 129, 'badge' => 'Promoção', 'desc' => 'Auriculares wireless com áudio imersivo.', 'full' => 'Som envolvente, cancelamento de ruído e autonomia para acompanhar trabalho e lazer.'),
            array('sku' => 'NT-006', 'title' => 'Titan Mechanical Keyboard', 'cat' => 'Gaming', 'price' => 119, 'sale' => 0, 'badge' => 'Mais vendido', 'desc' => 'Teclado mecânico RGB para gaming e produtividade.', 'full' => 'Switches mecânicos, iluminação RGB e construção robusta para gaming e programação.'),
            array('sku' => 'NT-007', 'title' => 'Core Gaming Mouse', 'cat' => 'Gaming', 'price' => 69, 'sale' => 0, 'badge' => 'Novo', 'desc' => 'Rato gaming ergonómico de alta precisão.', 'full' => 'Sensor preciso, botões programáveis e conforto para longas sessões.'),
            array('sku' => 'NT-008', 'title' => 'Vision 4K Monitor', 'cat' => 'Acessórios', 'price' => 349, 'sale' => 0, 'badge' => '', 'desc' => 'Monitor 4K para setup profissional.', 'full' => 'Imagem nítida, cores equilibradas e espaço visual para produtividade e criação.'),
            array('sku' => 'NT-009', 'title' => 'StreamCam Ultra', 'cat' => 'Acessórios', 'price' => 129, 'sale' => 0, 'badge' => '', 'desc' => 'Câmara para streaming e videochamadas.', 'full' => 'Alta definição, foco rápido e qualidade visual para reuniões, aulas e conteúdo.'),
            array('sku' => 'NT-010', 'title' => 'USB-C Dock Pro', 'cat' => 'Acessórios', 'price' => 89, 'sale' => 0, 'badge' => '', 'desc' => 'Hub USB-C completo para expandir o setup.', 'full' => 'Mais portas, mais produtividade e ligação simples para escritório ou home office.'),
            array('sku' => 'NT-011', 'title' => 'SmartBand Neo', 'cat' => 'Smart Home', 'price' => 79, 'sale' => 59, 'badge' => 'Promoção', 'desc' => 'Pulseira inteligente para saúde e atividade.', 'full' => 'Monitorização de atividade, sono e notificações num formato leve e elegante.'),
            array('sku' => 'NT-012', 'title' => 'HomeHub Mini', 'cat' => 'Smart Home', 'price' => 99, 'sale' => 0, 'badge' => 'Novo', 'desc' => 'Hub inteligente para controlar dispositivos.', 'full' => 'Centraliza automações e dispositivos inteligentes numa experiência simples.'),
            array('sku' => 'NT-013', 'title' => 'Thunder SSD 1TB', 'cat' => 'Acessórios', 'price' => 149, 'sale' => 0, 'badge' => '', 'desc' => 'Armazenamento rápido e portátil.', 'full' => 'Velocidade, resistência e praticidade para ficheiros grandes e backups.'),
            array('sku' => 'NT-014', 'title' => 'AeroPad Wireless Charger', 'cat' => 'Acessórios', 'price' => 39, 'sale' => 0, 'badge' => '', 'desc' => 'Carregador wireless compacto e moderno.', 'full' => 'Carregamento sem fios com design minimalista para secretária ou mesa de cabeceira.'),
            array('sku' => 'NT-015', 'title' => 'GameBox Controller', 'cat' => 'Gaming', 'price' => 74, 'sale' => 0, 'badge' => '', 'desc' => 'Comando sem fios para gaming confortável.', 'full' => 'Controlo preciso, ergonomia e bateria para sessões prolongadas.'),
            array('sku' => 'NT-016', 'title' => 'Lumina Smart Lamp', 'cat' => 'Smart Home', 'price' => 64, 'sale' => 0, 'badge' => '', 'desc' => 'Lâmpada inteligente RGB para ambientes modernos.', 'full' => 'Iluminação personalizável para trabalho, relaxamento e setups criativos.'),
        );

        foreach ($products as $product_data) {
            $existing_id = wc_get_product_id_by_sku($product_data['sku']);
            if (!$existing_id) {
                $existing_id = nexatech_find_product_by_title($product_data['title']);
            }

            $term = get_term_by('name', $product_data['cat'], 'product_cat');
            if (!$term) {
                continue;
            }

            $product = $existing_id ? wc_get_product($existing_id) : new WC_Product_Simple();
            if (!$product) {
                $product = new WC_Product_Simple();
            }

            $product->set_name($product_data['title']);
            $product->set_sku($product_data['sku']);
            $product->set_regular_price((string) $product_data['price']);
            if (!empty($product_data['sale'])) {
                $product->set_sale_price((string) $product_data['sale']);
            } else {
                $product->set_sale_price('');
            }
            $product->set_short_description(esc_html($product_data['desc']));
            $product->set_description(wp_kses_post($product_data['full']));
            $product->set_stock_status('instock');
            $product->set_manage_stock(false);
            $product->set_catalog_visibility('visible');
            $product->set_category_ids(array($term->term_id));
            $product->set_featured('Mais vendido' === $product_data['badge']);
            $saved_id = $product->save();

            if ($saved_id && !empty($product_data['badge'])) {
                wp_set_object_terms($saved_id, array($product_data['badge']), 'product_tag');
            }
        }
    }

    update_option('nexatech_setup_version', NEXATECH_VERSION);
}
add_action('init', 'nexatech_run_setup', 20);

function nexatech_activate()
{
    delete_option('nexatech_setup_version');
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'nexatech_activate');

function nexatech_deactivate()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'nexatech_deactivate');
