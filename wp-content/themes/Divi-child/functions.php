<?php
/**
 * Enqueue all YIF custom assets for frontend and Divi Builder.
 */
function yif_enqueue_custom_assets() {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    /**
     * === Popup / Form Styles ===
     * Handles everything related to enquiry popovers and Gravity Forms styling.
     */
    wp_enqueue_style(
        'yif-form-popover',
        $theme_uri . '/form-popover.css',
        array(), // no dependency on yif-custom.css anymore
        filemtime($theme_dir . '/form-popover.css')
    );

    /**
     * === Logo Block Styles ===
     * Keeps your logo carousel / section styles modular.
     */
    wp_enqueue_style(
        'yif-logo-block',
        $theme_uri . '/logo-block.css',
        array(),
        filemtime($theme_dir . '/logo-block.css')
    );




       /**
     * === Logo Block Styles ===
     * Keeps your logo carousel / section styles modular.
     */
    wp_enqueue_style(
        'yif-menu',
        $theme_uri . '/menu.css',
        array(),
        filemtime($theme_dir . '/menu.css')
    );






    /**
     * === Popup Functionality Script ===
     * Handles popup open/close logic and form behavior.
     */
    wp_enqueue_script(
        'yif-form-popover',
        $theme_uri . '/form-popover.js',
        array('jquery'),
        filemtime($theme_dir . '/form-popover.js'),
        true
    );




          /**
     * === Menu Script ===
     * Handles menu behavior.
     */
    wp_enqueue_script(
        'yif-menu',
        $theme_uri . '/menu.js',
        array('jquery'),
        filemtime($theme_dir . '/menu.js'),
        true
    );





}
add_action('wp_enqueue_scripts', 'yif_enqueue_custom_assets', 20);

/**
 * Also load assets inside Divi Visual Builder (iframe context).
 */
function yif_enqueue_custom_in_divi_builder() {
    if (isset($_GET['et_fb']) && $_GET['et_fb'] === '1') {
        yif_enqueue_custom_assets();
    }
}
add_action('admin_enqueue_scripts', 'yif_enqueue_custom_in_divi_builder');

/**
 * Ensure Gravity Forms JS is available even if forms are in hidden popups.
 */
add_action('wp_enqueue_scripts', function() {
    if (class_exists('GFForms')) {
        GFForms::include_addon_framework();
        wp_enqueue_script('gform_gravityforms');
    }
}, 25);

