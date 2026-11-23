<?php

namespace Smashballoon\Customizer;

if (!defined('ABSPATH')) {
    exit;
}
/**
 * Posts and pages a feed can be embedded into, for the Embed Feed modal picker.
 *
 * Generic port of Reviews Feed's SBR_Embed_Targets, with the capability passed
 * in: public post types, drafts included, grouped Pages first, then Posts, then
 * the rest. Rows are the shape EmbedTargetPicker draws.
 */
class EmbedTargets
{
    /**
     * Post types that are never an embed target.
     */
    const EXCLUDED = array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'wp_block', 'wp_template', 'wp_template_part', 'wp_global_styles', 'wp_navigation', 'oembed_cache', 'user_request');
    /**
     * Statuses offered, and the pill each one shows. Published rows get none.
     *
     * @param string $domain Text domain of the calling plugin.
     *
     * @return array<string, string>
     */
    public static function status_pills($domain = 'feeds-for-youtube')
    {
        return array('publish' => '', 'draft' => __('Draft', $domain), 'pending' => __('Pending', $domain), 'future' => __('Scheduled', $domain), 'private' => __('Private', $domain));
    }
    /**
     * Every place a feed can go, grouped and ordered for the picker.
     *
     * Returns titles of private and draft posts, so it refuses unless the
     * current user holds $capability, and drops any post they cannot edit,
     * because every row opens that post in the editor. That check runs per
     * row, so the query pages on until $limit editable rows are found.
     *
     * @param string $capability Capability that guards the calling screen.
     * @param int    $limit      Row ceiling, filterable, clamped to 500.
     * @param string $search     Optional search text, runs WP_Query `s`.
     * @param string $domain     Text domain of the calling plugin.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function get($capability, $limit = 200, $search = '', $domain = 'feeds-for-youtube')
    {
        if (empty($capability) || !current_user_can($capability)) {
            return array();
        }
        $limit = (int) apply_filters('sb_embed_targets_limit', $limit, (string) $search);
        $limit = $limit > 0 ? min($limit, 500) : 200;
        $types = get_post_types(array('public' => \true), 'objects');
        $names = array_values(array_diff(array_keys($types), self::EXCLUDED));
        $names = (array) apply_filters('sb_embed_target_post_types', $names);
        if (empty($names)) {
            return array();
        }
        $pills = self::status_pills($domain);
        $targets = array();
        // ponytail: at most 5 pages, so a user who can edit almost nothing cannot
        // make one request walk every post. Raise it if large sites miss rows.
        for ($page = 1; $page <= 5 && count($targets) < $limit; $page++) {
            $posts = get_posts(array('post_type' => $names, 'post_status' => array('publish', 'draft', 'pending', 'future', 'private'), 'posts_per_page' => $limit, 'paged' => $page, 's' => (string) $search, 'orderby' => $search !== '' ? 'relevance' : 'modified', 'order' => 'DESC', 'suppress_filters' => \false, 'no_found_rows' => \true, 'update_post_meta_cache' => \false, 'update_post_term_cache' => \false));
            foreach ($posts as $post) {
                if (count($targets) >= $limit) {
                    break;
                }
                if (!current_user_can('edit_post', $post->ID)) {
                    continue;
                }
                $object = isset($types[$post->post_type]) ? $types[$post->post_type] : null;
                $status = (string) $post->post_status;
                $title = wp_strip_all_tags((string) $post->post_title);
                $targets[] = array('id' => (int) $post->ID, 'title' => $title !== '' ? $title : sprintf(__('(no title) #%d', $domain), $post->ID), 'url' => (string) get_permalink($post), 'type' => (string) $post->post_type, 'typeLabel' => $object ? (string) $object->labels->singular_name : (string) $post->post_type, 'group' => $object ? (string) $object->labels->name : (string) $post->post_type, 'icon' => self::icon_for($post->post_type), 'status' => $status, 'statusLabel' => isset($pills[$status]) ? $pills[$status] : '');
            }
            if (count($posts) < $limit) {
                break;
            }
        }
        return self::sort_by_group($targets);
    }
    /**
     * Register GET sb-customizer/v1/embed-targets?search=&per_page=, the
     * picker's whole-site search. Rows come from get(), so the same edit_post
     * filter, sb_embed_targets_limit filter and 500-row clamp apply.
     *
     * @return void
     */
    public static function register_route()
    {
        register_rest_route('sb-customizer/v1', '/embed-targets', array('methods' => 'GET', 'callback' => function ($request) {
            return rest_ensure_response(self::get('edit_posts', (int) $request['per_page'], (string) $request['search']));
        }, 'permission_callback' => function () {
            return current_user_can('edit_posts');
        }, 'args' => array('search' => array('type' => 'string', 'default' => '', 'sanitize_callback' => 'sanitize_text_field'), 'per_page' => array('type' => 'integer', 'default' => 20, 'minimum' => 1, 'maximum' => 100))));
    }
    /**
     * Picker glyph for a post type: product, post or page.
     *
     * @param string $post_type Post type name.
     *
     * @return string
     */
    protected static function icon_for($post_type)
    {
        if (in_array($post_type, array('product', 'download'), \true)) {
            return 'product';
        }
        return $post_type === 'post' ? 'post' : 'page';
    }
    /**
     * Group rows, Pages first, Posts second, other groups by name. Rows keep
     * the query's recency order inside their group.
     *
     * @param array $targets Ungrouped rows.
     *
     * @return array
     */
    protected static function sort_by_group($targets)
    {
        $rank = array('page' => 0, 'post' => 1);
        $buckets = array();
        foreach ($targets as $target) {
            $buckets[$target['group']][] = $target;
        }
        uksort($buckets, function ($a, $b) use ($buckets, $rank) {
            $a_rank = isset($rank[$buckets[$a][0]['type']]) ? $rank[$buckets[$a][0]['type']] : 2;
            $b_rank = isset($rank[$buckets[$b][0]['type']]) ? $rank[$buckets[$b][0]['type']] : 2;
            return $a_rank === $b_rank ? strcasecmp($a, $b) : $a_rank - $b_rank;
        });
        return $buckets ? array_merge(...array_values($buckets)) : array();
    }
}
