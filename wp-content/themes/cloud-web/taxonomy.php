<?php
global $post;
global $post_template;

// Get the current taxonomy.
$term = get_queried_object();
$taxonomy = get_taxonomy($term->taxonomy);

// Get the post set as the template for this taxonomy.
$post_template = wp_get_taxonomy_template($taxonomy->name);

if ($post_template) {
    $post = $post_template;
}else {
    global $wp_query;
    $wp_query->set_404();
    return;
}

get_header();

get_body_content($post_template);

get_footer();