<?php
/**
 * Multisite Directory uninstaller.
 *
 * @package WordPress\Plugin\Multisite_Directory
 */

// Don't execute any uninstall code unless WordPress core requests it.
if (!defined('WP_UNINSTALL_PLUGIN')) { exit(); }

require_once 'includes/class-multisite-directory-entry.php';
require_once 'includes/class-multisite-directory-taxonomy.php';

switch_to_blog(1);

// Delete terms.
$terms = get_terms(Multisite_Directory_Taxonomy::name, array(
    'fields'     => 'ids',
    'hide_empty' => false,
));
foreach ($terms as $term_id) {
    wp_delete_term($term_id, Multisite_Directory_Taxonomy::name);
}

// Delete site directory entries.
$pages = get_pages(array(
    'post_type'   => Multisite_Directory_Entry::name,
    'post_status' => implode(',', array_keys(get_page_statuses()))
));
foreach ($pages as $page) {
    wp_delete_post($page->ID, true);
}

restore_current_blog();
