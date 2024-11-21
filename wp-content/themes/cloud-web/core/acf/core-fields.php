<?php
$loc = array (
    array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
    ),
);

wp_register_acf_fields('Archive Support', $loc, [
    wp_acf_field('About this Feature', 'message', [
        'message' => '<p>Enabling this feature will turn this page into an archive page for one or more post types, or for a taxonomy.</p>' .
            '<p>This will allow you to use <u>Listing Blocks</u> to display a list of posts.</p>'
    ]),
    wp_acf_field('Enable', 'true_false', [
        'default_value' => false,
        'ref' => 'enable_archive'
    ]),
    wp_acf_field('Setup', 'group', [
        'ref' => 'archive_setup',
        'sub_fields' => [
            wp_acf_field('Query Type', 'select', [
                'choices' => [
                    'posts' => 'Posts', 
                    'taxonomy' => 'Taxonomy'
                ],
                'required' => 1
            ]),
            wp_acf_field('Taxonomy', 'select', [
                'choices' => list_taxonomies(),
                'required' => 1,
                'show_if' => [
                    'field' => 'query_type',
                    'operator' => '==',
                    'value' => 'taxonomy'
                ]
            ]),
            wp_acf_field('Post Type', 'select', [
                'choices' => list_post_types(),
                'required' => 1,
                'show_if' => [
                    'field' => 'query_type',
                    'operator' => '==',
                    'value' => 'posts'
                ]
            ]),
            wp_acf_field('Posts Per Page', 'number', [
                'default_value' => get_option('posts_per_page')
            ])
        ],
        'show_if' => [
            'field' => 'enable_archive',
            'operator' => '==',
            'value' => '1' 
        ]
    ])
], 'side');