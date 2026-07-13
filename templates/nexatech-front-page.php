<?php
/**
 * NexaTech Store Demo – Fullscreen Front Page Template
 * Renders the NexaTech homepage without the active theme wrapper.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="NexaTech Store — marketplace premium de tecnologia com WooCommerce, catálogo, carrinho e checkout.">
<title>NexaTech Store — Premium Tech Marketplace</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'nexatech-fullpage' ); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}

echo do_shortcode( '[nexatech_homepage]' );

wp_footer();
?>
</body>
</html>
