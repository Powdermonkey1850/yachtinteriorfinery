<?php

/**
 * Regression check for EmbedTargets::get() paging.
 *
 * Framework-free self-check: run with `php tests/Embed_Targets_Test.php`.
 * Rows the user cannot edit are dropped after the query, so get() must keep
 * paging until it has $limit editable rows, and stop when posts run out.
 */
namespace SmashBalloon\YoutubeFeed\Vendor;

\define('ABSPATH', __DIR__);
// 30 posts, the user can edit only the even IDs.
$GLOBALS['posts'] = array();
for ($i = 1; $i <= 30; $i++) {
    $GLOBALS['posts'][] = (object) array('ID' => $i, 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => "P{$i}");
}
function current_user_can($cap, $id = 0)
{
    return $cap !== 'edit_post' || $id % 2 === 0;
}
function apply_filters($tag, $value)
{
    return $value;
}
function get_post_types()
{
    return array('page' => (object) array('labels' => (object) array('name' => 'Pages', 'singular_name' => 'Page')));
}
function get_posts($q)
{
    return \array_slice($GLOBALS['posts'], ($q['paged'] - 1) * $q['posts_per_page'], $q['posts_per_page']);
}
function __($text)
{
    return $text;
}
function wp_strip_all_tags($text)
{
    return $text;
}
function get_permalink($post)
{
    return '/?p=' . $post->ID;
}
namespace Smashballoon\Customizer;

require __DIR__ . '/../app/EmbedTargets.php';
$rows = \Smashballoon\Customizer\EmbedTargets::get('edit_posts', 10);
assert(count($rows) === 10, 'pages on until 10 editable rows are found');
assert($rows[0]['id'] === 2 && $rows[9]['id'] === 20, 'keeps query order, editable rows only');
$rows = \Smashballoon\Customizer\EmbedTargets::get('edit_posts', 20);
assert(count($rows) === 15, 'stops when posts run out');
echo "ok\n";
