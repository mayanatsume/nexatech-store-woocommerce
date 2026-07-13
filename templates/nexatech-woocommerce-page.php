<?php
/**
 * NexaTech Store Demo – WooCommerce Page Template
 * Renderiza Loja, Carrinho, Checkout, Conta, Produto e Categorias sem o tema padrão.
 */

if (!defined('ABSPATH')) {
    exit;
}

$home_url = esc_url(home_url('/'));
$shop_url = function_exists('wc_get_page_permalink') ? esc_url(wc_get_page_permalink('shop')) : esc_url(home_url('/shop/'));
$cart_url = function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : esc_url(home_url('/cart/'));
$checkout_url = function_exists('wc_get_checkout_url') ? esc_url(wc_get_checkout_url()) : esc_url(home_url('/checkout/'));
$account_url = function_exists('wc_get_page_permalink') ? esc_url(wc_get_page_permalink('myaccount')) : esc_url(home_url('/my-account/'));
$categories_url = esc_url(home_url('/categorias/'));

$cart_count = 0;
if (function_exists('WC') && WC()->cart) {
    $cart_count = WC()->cart->get_cart_contents_count();
}

$cats = function_exists('nexatech_get_product_categories') ? nexatech_get_product_categories() : array();

$page_title = 'Loja';

if (function_exists('is_cart') && is_cart()) {
    $page_title = 'Carrinho';
} elseif (function_exists('is_checkout') && is_checkout()) {
    $page_title = 'Checkout';
} elseif (function_exists('is_account_page') && is_account_page()) {
    $page_title = 'Minha Conta';
} elseif (is_page('categorias')) {
    $page_title = 'Categorias';
} elseif (function_exists('is_product') && is_product()) {
    $page_title = get_the_title();
} elseif (function_exists('is_product_category') && is_product_category()) {
    $page_title = single_term_title('', false);
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($page_title); ?> — NexaTech Store</title>
    <?php wp_head(); ?>

    <style>
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #050816 !important;
            color: #F8FAFC !important;
            overflow-x: hidden !important;
        }

        body.nexatech-woo-page {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 12% 8%, rgba(124, 58, 237, 0.26), transparent 30%),
                radial-gradient(circle at 88% 12%, rgba(6, 182, 212, 0.18), transparent 28%),
                radial-gradient(circle at 50% 95%, rgba(245, 199, 107, 0.10), transparent 30%),
                #050816 !important;
        }

        .nt-topbar {
            background: #070A13;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            color: #94A3B8;
            font-size: 12px;
            letter-spacing: .14em;
            text-transform: uppercase;
            padding: 10px 24px;
            text-align: center;
        }

        .nt-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(11, 16, 32, .88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255, 255, 255, .10);
        }

        .nt-nav {
            max-width: 1320px;
            margin: 0 auto;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .nt-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #F8FAFC;
            text-decoration: none;
            font-weight: 900;
            font-size: 24px;
            letter-spacing: -.04em;
        }

        .nt-logo-mark {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(124, 58, 237, .28), rgba(6, 182, 212, .12));
            border: 1px solid rgba(124, 58, 237, .55);
            box-shadow: 0 0 30px rgba(124, 58, 237, .25);
        }

        .nt-logo span span {
            color: #7C3AED;
        }

        .nt-logo strong {
            color: #06B6D4;
        }

        .nt-menu {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .nt-menu a {
            color: #94A3B8;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: .25s ease;
        }

        .nt-menu a:hover {
            color: #06B6D4;
        }

        .nt-cart-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 22px;
            border-radius: 999px;
            background: rgba(124, 58, 237, .18);
            border: 1px solid rgba(124, 58, 237, .55);
            color: #F8FAFC !important;
            box-shadow: 0 0 28px rgba(124, 58, 237, .20);
        }

        .nt-cart-count {
            width: 25px;
            height: 25px;
            border-radius: 999px;
            background: #06B6D4;
            color: #020617;
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 900;
        }

        .nt-mobile-toggle {
            display: none;
            border: 0;
            background: transparent;
            color: #F8FAFC;
            cursor: pointer;
            font-size: 28px;
        }

        .nt-mobile-menu {
            display: none;
            padding: 0 28px 22px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .nt-mobile-menu a {
            display: block;
            padding: 12px 0;
            color: #F8FAFC;
            text-decoration: none;
            font-weight: 700;
        }

        .nt-category-menu {
            position: relative;
        }

        .nt-category-trigger {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #F8FAFC;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 999px;
            padding: 10px 14px;
            font-weight: 900;
            cursor: pointer;
        }

        .nt-category-trigger:hover {
            color: #67E8F9;
            border-color: rgba(6, 182, 212, .55);
        }

        .nt-category-dropdown {
            position: absolute;
            top: calc(100% + 14px);
            left: 0;
            width: min(680px, 90vw);
            display: none;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            padding: 16px;
            border-radius: 24px;
            background: rgba(11, 16, 32, .96);
            border: 1px solid rgba(255, 255, 255, .12);
            box-shadow: 0 30px 90px rgba(0, 0, 0, .38);
            backdrop-filter: blur(22px);
            z-index: 999;
        }

        .nt-category-menu:hover .nt-category-dropdown,
        .nt-category-menu.nt-open .nt-category-dropdown {
            display: grid;
        }

        .nt-category-dropdown a,
        .nt-mobile-category-list a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 16px;
            color: #F8FAFC;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
            text-decoration: none;
        }

        .nt-category-dropdown a:hover,
        .nt-mobile-category-list a:hover {
            border-color: rgba(6, 182, 212, .45);
            background: rgba(6, 182, 212, .08);
        }

        .nt-category-dropdown svg,
        .nt-mobile-category-list svg {
            width: 28px;
            height: 28px;
            stroke: #06B6D4;
            fill: none;
            stroke-width: 2;
            flex: 0 0 auto;
        }

        .nt-category-dropdown strong,
        .nt-mobile-category-list strong {
            display: block;
            font-size: 14px;
        }

        .nt-category-dropdown small,
        .nt-mobile-category-list small {
            display: block;
            color: #94A3B8;
            font-size: 12px;
            margin-top: 2px;
        }

        .nt-mobile-category-title {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            color: #67E8F9;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 900;
        }

        .nt-mobile-category-list {
            display: grid;
            gap: 8px;
            margin-top: 8px;
        }

        .nt-wc-product-visual {
            min-height: 220px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 20% 10%, rgba(6, 182, 212, .25), transparent 38%), radial-gradient(circle at 80% 70%, rgba(124, 58, 237, .35), transparent 42%), #0b1020;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .nt-wc-product-visual .nt-product-visual {
            width: 100%;
            height: 220px;
        }

        .nt-wc-product-img {
            width: 100%;
            aspect-ratio: 1.25/1;
            object-fit: cover;
            border-radius: 22px !important;
            margin-bottom: 18px !important;
        }

        .nt-woo-hero {
            position: relative;
            padding: 76px 28px 48px;
            overflow: hidden;
        }

        .nt-woo-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, .035) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, .035) 1px, transparent 1px);
            background-size: 58px 58px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, .8), transparent 90%);
            pointer-events: none;
        }

        .nt-woo-hero-inner {
            position: relative;
            max-width: 1320px;
            margin: 0 auto;
        }

        .nt-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #06B6D4;
            border: 1px solid rgba(6, 182, 212, .35);
            background: rgba(6, 182, 212, .08);
            padding: 9px 18px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase;
            margin-bottom: 22px;
        }

        .nt-woo-title {
            font-size: clamp(3rem, 7vw, 6.8rem);
            line-height: .92;
            letter-spacing: -.07em;
            margin: 0;
            color: #F8FAFC;
        }

        .nt-woo-title span {
            background: linear-gradient(90deg, #7C3AED, #06B6D4, #F5C76B);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nt-woo-subtitle {
            max-width: 720px;
            color: #94A3B8;
            font-size: 19px;
            line-height: 1.7;
            margin: 24px 0 0;
        }

        .nt-woo-shell {
            max-width: 1320px;
            margin: 0 auto;
            padding: 30px 28px 90px;
        }

        .nt-woo-card {
            background: rgba(15, 23, 42, .78);
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 30px;
            padding: 28px;
            box-shadow: 0 30px 90px rgba(0, 0, 0, .25);
            backdrop-filter: blur(18px);
        }

        .nt-woo-card .woocommerce,
        .nt-woo-card .woocommerce-page {
            color: #F8FAFC;
        }

        .woocommerce ul.products {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 26px !important;
            margin: 0 !important;
        }

        .woocommerce ul.products li.product {
            width: auto !important;
            float: none !important;
            margin: 0 !important;
            padding: 18px !important;
            border-radius: 28px !important;
            background: rgba(17, 24, 39, .82) !important;
            border: 1px solid rgba(255, 255, 255, .10) !important;
            overflow: hidden !important;
            transition: .25s ease !important;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, .02), 0 20px 60px rgba(0, 0, 0, .22);
        }

        .woocommerce ul.products li.product:hover {
            transform: translateY(-6px);
            border-color: rgba(6, 182, 212, .55) !important;
            box-shadow: 0 26px 90px rgba(6, 182, 212, .12), 0 0 45px rgba(124, 58, 237, .16);
        }

        .woocommerce ul.products li.product img {
            border-radius: 22px !important;
            background: linear-gradient(135deg, rgba(6, 182, 212, .20), rgba(124, 58, 237, .25)) !important;
            aspect-ratio: 1.25 / 1;
            object-fit: cover;
            margin-bottom: 18px !important;
        }

        .woocommerce-loop-product__title {
            color: #F8FAFC !important;
            font-size: 20px !important;
            font-weight: 800 !important;
            line-height: 1.2 !important;
        }

        .woocommerce ul.products li.product .price {
            color: #F5C76B !important;
            font-size: 20px !important;
            font-weight: 900 !important;
        }

        .woocommerce ul.products li.product .button,
        .woocommerce a.button,
        .woocommerce button.button,
        .woocommerce input.button,
        .woocommerce #respond input#submit,
        .woocommerce #place_order {
            background: linear-gradient(135deg, #06B6D4, #67E8F9) !important;
            color: #020617 !important;
            border: 0 !important;
            border-radius: 999px !important;
            font-weight: 900 !important;
            padding: 13px 22px !important;
            transition: .25s ease !important;
            text-decoration: none !important;
        }

        .woocommerce ul.products li.product .button:hover,
        .woocommerce a.button:hover,
        .woocommerce button.button:hover,
        .woocommerce input.button:hover,
        .woocommerce #place_order:hover {
            background: linear-gradient(135deg, #7C3AED, #06B6D4) !important;
            color: #FFFFFF !important;
            transform: translateY(-2px);
        }

        .woocommerce table.shop_table,
        .woocommerce-cart-form,
        .cart_totals,
        .woocommerce-checkout-review-order,
        .woocommerce form.checkout,
        .woocommerce-MyAccount-content,
        .woocommerce-MyAccount-navigation {
            background: rgba(17, 24, 39, .78) !important;
            color: #F8FAFC !important;
            border: 1px solid rgba(255, 255, 255, .10) !important;
            border-radius: 24px !important;
            padding: 22px !important;
            overflow: hidden;
        }

        .woocommerce table.shop_table th,
        .woocommerce table.shop_table td {
            color: #F8FAFC !important;
            border-color: rgba(255, 255, 255, .10) !important;
        }

        .woocommerce form .form-row input.input-text,
        .woocommerce form .form-row textarea,
        .woocommerce form .form-row select,
        .select2-container--default .select2-selection--single {
            background: rgba(2, 6, 23, .78) !important;
            border: 1px solid rgba(255, 255, 255, .14) !important;
            color: #F8FAFC !important;
            border-radius: 16px !important;
            padding: 13px 15px !important;
        }

        .woocommerce-message,
        .woocommerce-info,
        .woocommerce-error {
            background: rgba(15, 23, 42, .95) !important;
            color: #F8FAFC !important;
            border-top-color: #06B6D4 !important;
            border-radius: 18px !important;
        }

        .woocommerce-Price-amount {
            color: #F5C76B;
            font-weight: 900;
        }

        .nt-footer {
            border-top: 1px solid rgba(255, 255, 255, .10);
            background: #050816;
            color: #94A3B8;
            padding: 38px 28px;
            text-align: center;
            font-size: 14px;
        }

        @media (max-width:1024px) {
            .woocommerce ul.products {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width:760px) {
            .nt-menu {
                display: none;
            }

            .nt-mobile-toggle {
                display: block;
            }

            .nt-mobile-menu.nt-open {
                display: block;
            }

            .nt-woo-hero {
                padding-top: 54px;
            }

            .woocommerce ul.products {
                grid-template-columns: 1fr !important;
            }

            .nt-woo-card {
                padding: 16px;
                border-radius: 22px;
            }
        }
    </style>
</head>

<body <?php body_class('nexatech-woo-page'); ?>>
    <?php
    if (function_exists('wp_body_open')) {
        wp_body_open();
    }
    ?>

    <div class="nt-topbar">
        ✦ Premium Tech Marketplace &nbsp;&nbsp; ✦ Envio rápido &nbsp;&nbsp; ✦ Pagamentos seguros &nbsp;&nbsp; ✦ Suporte
        especializado
    </div>

    <header class="nt-header" id="nexatech-header">
        <div class="nt-nav">
            <a class="nt-logo" href="<?php echo $home_url; ?>">
                <span class="nt-logo-mark">✦</span>
                <span>Nexa<span>Tech</span> <strong>Store</strong></span>
            </a>

            <nav class="nt-menu">
                <a href="<?php echo $home_url; ?>">Home</a>
                <?php echo function_exists('nexatech_render_category_dropdown') ? nexatech_render_category_dropdown($cats) : '<a href="' . esc_url($categories_url) . '">Categorias</a>'; ?>
                <a href="<?php echo $cart_url; ?>">Carrinho</a>
                <a href="<?php echo $checkout_url; ?>">Checkout</a>
                <a href="<?php echo $account_url; ?>">Conta</a>
                <a class="nt-cart-pill" href="<?php echo $cart_url; ?>">🛒 Carrinho <span
                        class="nt-cart-count"><?php echo esc_html($cart_count); ?></span></a>
            </nav>

            <button class="nt-mobile-toggle" id="nexatech-mobile-toggle" aria-label="Abrir menu">☰</button>
        </div>

        <nav class="nt-mobile-menu" id="nexatech-mobile-menu">
            <a href="<?php echo $home_url; ?>">Home</a>
            <?php echo function_exists('nexatech_render_mobile_categories') ? nexatech_render_mobile_categories($cats) : ''; ?>
            <a href="<?php echo $cart_url; ?>">Carrinho</a>
            <a href="<?php echo $checkout_url; ?>">Checkout</a>
            <a href="<?php echo $account_url; ?>">Conta</a>
        </nav>
    </header>

    <section class="nt-woo-hero">
        <div class="nt-woo-hero-inner">
            <div class="nt-kicker">NexaTech Store</div>
            <h1 class="nt-woo-title"><?php echo esc_html($page_title); ?> <span>premium</span></h1>
            <p class="nt-woo-subtitle">Tecnologia selecionada para produtividade, gaming, smart home e setups modernos,
                com uma experiência de compra rápida, elegante e responsiva.</p>
        </div>
    </section>

    <main class="nt-woo-shell">
        <div class="nt-woo-card">
            <?php
            if (!function_exists('WC')) {
                echo '<p>WooCommerce não está ativo.</p>';
            } elseif (is_page('categorias')) {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            } elseif (function_exists('is_cart') && is_cart()) {
                echo do_shortcode('[woocommerce_cart]');
            } elseif (function_exists('is_checkout') && is_checkout()) {
                echo do_shortcode('[woocommerce_checkout]');
            } elseif (function_exists('is_account_page') && is_account_page()) {
                echo do_shortcode('[woocommerce_my_account]');
            } elseif (function_exists('woocommerce_content')) {
                woocommerce_content();
            } else {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            }
            ?>
        </div>
    </main>

    <footer class="nt-footer">
        <strong style="color:#F8FAFC;">NexaTech Store</strong> — tecnologia premium para setups digitais modernos.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggle = document.getElementById('nexatech-mobile-toggle');
            var menu = document.getElementById('nexatech-mobile-menu');

            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    menu.classList.toggle('nt-open');
                });
            }
        });
    </script>

    <?php wp_footer(); ?>
</body>

</html>