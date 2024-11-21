<?php

// Get Taxonomy Template
function wp_get_taxonomy_template ($taxonomy) {
    $query = new WP_Query([
        'post_type' => 'page',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => 'enable_archive',
                'value' => 1,
            ],
            [
                'key' => 'archive_setup_query_type',
                'value' => 'taxonomy',
            ],
            [
                'key' => 'archive_setup_taxonomy',
                'compare' => 'LIKE',
                'value' => $taxonomy,
            ],
        ],
    ]);
    
    return $query->have_posts() ? $query->posts[0] : null;
}

// Get Post Type Template
function wp_get_post_type_archive($post_type) {
    $query = new WP_Query([
        'post_type' => 'page',
        'paged' => 1,
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => 'enable_archive',
                'value' => 1,
            ],
            [
                'key' => 'archive_setup_post_type',
                'value' => $post_type,
            ],
        ],
    ]);

    if (!$query->have_posts()) {
        return null;
    }

    // Get the first post.
    $post = $query->posts[0];

    // Prepare the path to the post.
    $path = trim(parse_url(get_the_permalink($post->ID), PHP_URL_PATH), '/');

    return [
        'ID' => $post->ID,
        'title' => $post->post_title,
        'permalink' => get_the_permalink($post->ID),
        'path' => $path,
        'post' => $post,
    ];
}

function wp_get_archive_data() {
    global $post;
    global $post_template;

    $post = ($post_template) ? $post_template : $post;

    // Get the archive configuration for the current page.
    $archive_enabled = get_field('enable_archive', $post->ID);
    $archive_setup = get_field('archive_setup', $post->ID);

    // Bail early if the archive is not enabled or the setup is not valid.
    if (!$archive_enabled || !$archive_setup) {
        return null;
    }

    // The archive result.
    $result = [
        'filters' => [],
    ];

    // The parameters that will be used to run the query.
    $params = [
        'post__not_in' => [$post->ID],
        'posts_per_page' => $archive_setup['posts_per_page'],
    ];

    if (isset($_GET['search_term'])) {
        $params['s'] = $_GET['search_term'];
    }

    if (get_query_var('paged')) {
        $params['paged'] = get_query_var('paged');
    }

    if (get_post_type() === 'page' || is_tax()) {
        if ($archive_setup['query_type'] === 'taxonomy') {
            $term = get_queried_object();

            if (!$term) {
                $taxonomy = $archive_setup['taxonomy'];

                $terms = get_terms([
                    'taxonomy' => $taxonomy,
                    'hide_empty' => true,
                ]);

                if (!empty($terms) && !is_wp_error($terms)) {
                    $term = $terms[0];
                }
            }

            if ($term) {
                $taxonomy = get_taxonomy($term->taxonomy);

                $params['post_type'] = $taxonomy->object_type;
                $params['tax_query'] = [
                    [
                        'taxonomy' => $term->taxonomy,
                        'field' => 'slug',
                        'terms' => $term->slug,
                    ],
                ];

                // Add taxonomy filters.
                $result['filters'][$taxonomy->name] = assemble_filters_from_tax($taxonomy);
            }
        }

        if ($archive_setup['query_type'] === 'posts') {
            $params['post_type'] = $archive_setup['post_type'];

            // Add taxonomy filters.
            $taxonomies = get_object_taxonomies($params['post_type'], 'objects');

            foreach ($taxonomies as $taxonomy) {
                $result['filters'][$taxonomy->name] = assemble_filters_from_tax($taxonomy);
            }
        }
    }

    // Get the posts.
    $result['query_params'] = $params;

    return $result;
}

// Prevent Taxonomy Archive Access
function wp_prevent_taxonomy_template_access() {
    if (!is_page() && !is_single()) return;

    $archive_enabled = get_field('enable_archive', get_the_ID());
    $archive_setup = get_field('archive_setup', get_the_ID());

    if ($archive_enabled && $archive_setup && $archive_setup['query_type'] === 'taxonomy') {
        global $wp_query;
        $wp_query->set_404();
    }
}

add_action('template_redirect', 'wp_prevent_taxonomy_template_access');

// Filter page states for archive pages.
function wp_filter_archive_page_states($post_states, $post) {
    $archive_enabled = get_field('enable_archive', $post->ID);
    $archive_setup = get_field('archive_setup', $post->ID);

    if (!is_admin() || !$archive_enabled || !$archive_setup) return $post_states;

    $arch_obj = ($archive_setup['query_type'] === 'posts') ? get_post_type_object($archive_setup['post_type']) : get_taxonomy($archive_setup['taxonomy']);

    $post_states[] = "$arch_obj->label Archive";

    return $post_states;
}

add_filter('display_post_states', 'wp_filter_archive_page_states', 10, 2);

// Assemble Filters from Taxonomy
function assemble_filters_from_tax($taxonomy) {
    $current_term = (is_tax()) ? get_queried_object() : null;

    $terms = get_terms([
        'taxonomy' => $taxonomy->name,
        'hide_empty' => true,
    ]);

    $filters = [];

    if (!empty($terms)) {
        $filters['options'] = array_map(function ($t) use ($current_term, $taxonomy) {
            return [
                'label' => $t->name,
                'value' => get_term_link($t->term_id, $taxonomy->name),
                'active' => (!is_null($current_term)) ? $t->term_id === $current_term->term_id : false,
            ];
        }, $terms);

        $filters['label'] = $taxonomy->label;
    }

    return $filters;
} 