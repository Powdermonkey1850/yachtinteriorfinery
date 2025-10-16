<?php
/**
 * Enqueue all YIF custom assets for frontend and Divi Builder.
 */
function yif_enqueue_custom_assets() {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    // === Core Custom Stylesheet ===
    wp_enqueue_style(
        'yif-custom',
        $theme_uri . '/yif-custom.css',
        array(),
        filemtime($theme_dir . '/yif-custom.css')
    );

    // === Popup / Form Styles ===
    wp_enqueue_style(
        'yif-form-popover',
        $theme_uri . '/form-popover.css',
        array('yif-custom'), // ensures order
        filemtime($theme_dir . '/form-popover.css')
    );

    // === Logo Block Styles ===
    wp_enqueue_style(
        'yif-logo-block',
        $theme_uri . '/logo-block.css',
        array('yif-custom'), // ensures it loads after main styles
        filemtime($theme_dir . '/logo-block.css')
    );

    // === Custom JavaScript ===
    wp_enqueue_script(
        'yif-custom-js',
        $theme_uri . '/yif-custom.js',
        array('jquery'),
        filemtime($theme_dir . '/yif-custom.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'yif_enqueue_custom_assets', 20);

/**
 * Also load assets inside Divi Visual Builder (iframe context).
 */
function yif_enqueue_custom_in_divi_builder() {
    if ( isset($_GET['et_fb']) && $_GET['et_fb'] === '1' ) {
        yif_enqueue_custom_assets();
    }
}
add_action('admin_enqueue_scripts', 'yif_enqueue_custom_in_divi_builder');

