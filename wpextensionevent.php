<?php
/**
 * Plugin Name: WP Extension Event
 * Description: Une extension pour gérer et afficher des événements avec un système de filtrage avancé.
 * Version: 1.0.0
 * Author: VIRY Brandon
 * Text Domain: wpextensionevent
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'WP_EXTENSION_EVENT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_EXTENSION_EVENT_URL', plugin_dir_url( __FILE__ ) );

// Include core classes
require_once WP_EXTENSION_EVENT_PATH . 'includes/class-event-post-type.php';
require_once WP_EXTENSION_EVENT_PATH . 'includes/class-event-shortcode.php';
require_once WP_EXTENSION_EVENT_PATH . 'includes/class-event-admin.php';
require_once WP_EXTENSION_EVENT_PATH . 'includes/class-event-template.php';
require_once WP_EXTENSION_EVENT_PATH . 'includes/class-event-template-manager.php';

// Initialize the plugin
function wpextensionevent_init() {
    $post_type = new WPExtensionEvent_Post_Type();
    $post_type->init();

    $shortcode = new WPExtensionEvent_Shortcode();
    $shortcode->init();

    $template = new WPExtensionEvent_Template();
    $template->init();

    $template_manager = new WPExtensionEvent_Template_Manager();
    $template_manager->init();

    if ( is_admin() ) {
        $admin = new WPExtensionEvent_Admin();
        $admin->init();
    }
}
add_action( 'plugins_loaded', 'wpextensionevent_init' );

// Activation hook to flush rewrite rules
register_activation_hook( __FILE__, 'wpextensionevent_activate' );
function wpextensionevent_activate() {
    $post_type = new WPExtensionEvent_Post_Type();
    $post_type->register_post_type();
    flush_rewrite_rules();
}
