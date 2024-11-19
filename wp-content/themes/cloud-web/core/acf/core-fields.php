<?php
$loc = array (
    array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
    ),
);

wp_register_acf_fields('Archive Support', $loc, [
    wp_acf_field('Enable', 'true_false', [
        'default_value' => false
    ]),
    wp_acf_field('Setup', 'group', [
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
                    'field' => 'Query Type',
                    'operator' => '==',
                    'value' => 'taxonomy'
                ]
            ]),
            wp_acf_field('Post Type', 'select', [
                'choices' => list_post_types(),
                'required' => 1,
                'show_if' => [
                    'field' => 'Query Type',
                    'operator' => '==',
                    'value' => 'posts'
                ]
            ])
        ],
        'show_if' => [
            'field' => 'enable',
            'operator' => '==',
            'value' => '1' 
        ]
    ])
], 'side');