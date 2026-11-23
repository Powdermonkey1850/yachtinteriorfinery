<?php

namespace Smashballoon\Customizer;

if (!defined('ABSPATH')) {
    exit;
}
/**
 * The customizer's Embed Feed modal, for builders that are not this React app.
 *
 * Enqueues build/embed-modal (the same EmbedModal component, bundled with its own
 * React 18) and localizes `sbEmbedModalConfig`. The builder then calls
 * `window.SBEmbedModal.open( { feedId: 12 } )` from its Embed button. When
 * enqueue() returns false (WordPress older than 5.8, or the bundle is missing)
 * window.SBEmbedModal is never defined, and the builder copies the shortcode.
 * `window.SBEmbedModal.supported` carries is_supported() for callers that
 * check it.
 *
 *     EmbedModal::enqueue( array(
 *         'capability'  => 'manage_youtube_feed_options',
 *         'block_title' => __( 'YouTube Feed', 'feeds-for-youtube' ),
 *         'wizard_arg'  => 'sby_wizard',
 *         'shortcode'   => '[youtube-feed feed=%s]',
 *     ) );
 *
 * build/embed-modal is built, not hand written. This line has no React app, so
 * its source is the v2 line's sb-common/sb-customizer (src/Standalone/embed-modal.js),
 * at commit 2b92a1c (awesomemotive/sb-customizer#254).
 * The committed bundle differs from the v2 one in its text domain and in
 * build/embed-modal/master.patch (the supported flag, canCreatePages, and ''
 * for a missing shortcode or wizardArg), which v2 does not carry. To rebuild it,
 * byte for byte:
 *
 *     git worktree add /tmp/v2 2b92a1c && cd /tmp/v2/sb-common/sb-customizer
 *     npm ci
 *     grep -rlE "'(reviews-feed|sb-customizer)'" src \
 *         | xargs sed -i -E "s/'(reviews-feed|sb-customizer)'/'feeds-for-youtube'/g"
 *     patch -p1 < <this package>/build/embed-modal/master.patch
 *     node build-embed-modal.mjs
 *     cp ../../build/embed-modal/embed-modal.* <this package>/build/embed-modal/
 *
 * Check the script dependencies below against the bundle's window.wp use when
 * refreshing it.
 *
 * Asset URLs come from this file's own location, so they follow whichever copy of
 * the package (vendored, scoped or not) actually loaded the class.
 */
class EmbedModal
{
    const HANDLE = 'sb-embed-modal';
    /**
     * Enqueue the modal and localize its contract.
     *
     * @param array $args {
     *     @type string $capability  Capability that guards the builder screen.
     *     @type string $block_title The plugin's block title, for the Site Editor hint.
     *     @type string $wizard_arg  Query arg the editor reads to insert the block, carries the feed id.
     *     @type string $shortcode   Shortcode template, `%s` is replaced by the feed id.
     *     @type string $text_domain Text domain of the bundle's strings and the picker's pills.
     *     @type string $languages   Directory of the plugin's JSON translations, '' for WP_LANG_DIR only.
     * }
     *
     * @return bool False when the modal was not enqueued, see is_supported().
     */
    public static function enqueue($args)
    {
        $args = wp_parse_args($args, array('capability' => 'manage_options', 'block_title' => '', 'wizard_arg' => '', 'shortcode' => '', 'text_domain' => 'feeds-for-youtube', 'languages' => defined('SBY_PLUGIN_DIR') ? SBY_PLUGIN_DIR . 'languages/' : ''));
        $root = dirname(__DIR__);
        $dir = $root . '/build/embed-modal/';
        $url = plugins_url('build/embed-modal/', $root . '/composer.json');
        if (!self::is_supported() || !file_exists($dir . 'embed-modal.css') || !file_exists($dir . 'embed-modal.js')) {
            return \false;
        }
        wp_enqueue_style(self::HANDLE, $url . 'embed-modal.css', array(), (string) filemtime($dir . 'embed-modal.css'));
        wp_enqueue_script(self::HANDLE, $url . 'embed-modal.js', array('wp-i18n', 'wp-api-fetch'), (string) filemtime($dir . 'embed-modal.js'), \true);
        wp_set_script_translations(self::HANDLE, $args['text_domain'], $args['languages']);
        $block_theme = function_exists('wp_is_block_theme') && wp_is_block_theme();
        wp_localize_script(self::HANDLE, 'sbEmbedModalConfig', array('targets' => \Smashballoon\Customizer\EmbedTargets::get($args['capability'], 200, '', $args['text_domain']), 'shortcode' => (string) $args['shortcode'], 'wizardArg' => sanitize_key($args['wizard_arg']), 'blockTitle' => wp_strip_all_tags((string) $args['block_title']), 'adminPostURL' => admin_url('post.php'), 'siteEditorURL' => $block_theme ? admin_url('site-editor.php') : '', 'widgetsURL' => !$block_theme && current_theme_supports('widgets') ? admin_url('widgets.php') : '', 'canCreatePages' => current_user_can('edit_pages'), 'supported' => self::is_supported()));
        return \true;
    }
    /**
     * Whether this WordPress can run the whole flow. The bundle carries its own
     * React 18, but "Add and Go to Page" inserts the plugin's feed block, which
     * registers through register_block_type( $dir ) and so needs block.json
     * support, from WordPress 5.8. Older sites keep the shortcode fallback.
     *
     * @return bool
     */
    public static function is_supported()
    {
        return version_compare(get_bloginfo('version'), '5.8', '>=');
    }
}
