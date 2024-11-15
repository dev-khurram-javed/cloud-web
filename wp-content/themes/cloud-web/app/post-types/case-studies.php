<?php
wp_register_custom_post_type('Case Studies', 'images-alt2', [
    wp_acf_field('Project Roles', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Role', 'text')
        ],
        'required' => '1',
        'max' => '6',
        'button_label' => 'Add New Role'
    ])
]);