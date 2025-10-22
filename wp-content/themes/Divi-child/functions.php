<?php
/**
 * Enqueue all YIF custom assets for frontend and Divi Builder.
 */
function yif_enqueue_custom_assets() {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    /**
     * Determine global version for cache-busting.
     * In development → uses filemtime() for automatic cache busting.
     * In production → uses fixed constant for stable asset caching.
     */
    if (defined('WP_DEBUG') && WP_DEBUG) {
        // Get all CSS and JS files in your theme directories
        $css_files = glob($theme_dir . '/css/*.css');
        $js_files  = glob($theme_dir . '/js/*.js');

        // Merge arrays and find the latest modification time
        $files = array_merge($css_files ?: [], $js_files ?: []);
        $latest_mod_time = 0;

        foreach ($files as $file) {
            $mtime = filemtime($file);
            if ($mtime > $latest_mod_time) {
                $latest_mod_time = $mtime;
            }
        }

        // Use latest modification timestamp for cache busting
        $version = $latest_mod_time ?: time(); // fallback to current time if no files found
    } else {
        // Production mode — manual version control
        $version = '1b'; // update manually when deploying
    }

    /**
     * === Styles ===
     */
    // Load Divi parent stylesheet
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Load child theme's main style.css (root file)
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'), $version);


    // Load custom modular CSS files from /css folder
    //
    wp_enqueue_style('style', $theme_uri . '/style.css', array(), $version);
    wp_enqueue_style('yif-form-popover', $theme_uri . '/css/form-popover.css', array(), $version);
    wp_enqueue_style('old-divi-theme', $theme_uri . '/css/old-divi-theme.css', array(), $version);
//    wp_enqueue_style('yif-form-popover', $theme_uri . '/css/form-popover.css', array(), $version);
    wp_enqueue_style('yif-logo-block', $theme_uri . '/css/logo-block.css', array(), $version);
    wp_enqueue_style('yif-menu', $theme_uri . '/css/menu.css', array(), $version);
    wp_enqueue_style('section-boy-girl', $theme_uri . '/css/section-boy-girl.css', array(), $version);
    wp_enqueue_style('custom-header', $theme_uri . '/css/custom-header.css', array(), $version);
    wp_enqueue_style('temp', $theme_uri . '/css/temp.css', array(), $version);
    wp_enqueue_style('explore-section-pages', $theme_uri . '/css/explore-section-pages.css', array(), $version);




    /**
     * === Scripts ===
     */
    wp_enqueue_script('yif-form-popover', $theme_uri . '/js/form-popover.js', array('jquery'), $version, true);
    wp_enqueue_script('yif-menu', $theme_uri . '/js/menu.js', array('jquery'), $version, true);
}
add_action('wp_enqueue_scripts', 'yif_enqueue_custom_assets', 20);

/**
 * Load assets inside Divi Visual Builder (iframe context).
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

