<?php 

// Get the list of menus.
$menu_items = ['' => 'Select Menu'];

foreach (wp_get_nav_menus() as $key => $menu) {
    $menu_items[$menu->slug] = $menu->name;
}

// Parent Page
register_option_page('Site Options', [], [
    'position' => '100'
]);

// Sub Pages
register_option_page('Default Pages', [
    wp_acf_field('Default Pages', 'group', [
        'required' => 1,
        'sub_fields' => [
            wp_acf_field('404 (Not Found)', 'post_object', [
                'ref' => '404',
                'post_type' => 'page'
            ]),
            wp_acf_field('Search', 'post_object', [
                'post_type' => 'page',
            ])
        ]
    ])
], [ 'parent' => 'site-options' ]);

// Register Header Options
register_option_page('Header', [
    wp_acf_field('Logo', 'image', [
        'required' => 1
    ]),
    wp_acf_field('Menu', 'select', [
        'choices' => $menu_items,
        'required' => 1
    ], '50'),
    wp_acf_field('Phone', 'text', [], '50'),
], [ 'parent' => 'site-options' ]);

// Register Footer Options
register_option_page('Footer', [
    wp_acf_field('Social Media', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Icon', 'select', [
                'choices' => [
                    'facebook' => 'Facebook',
                    'instagram' => 'Instagram',
                    'google' => 'Google',
                    'linkedin' => 'Linkedin',
                    'tiktok' => 'TikTok',
                    'twitter' => 'Twitter',
                    'youtube' => 'Youtube'
                ]
            ], '50'),
            wp_acf_field('Link', 'url', [], '50')
        ],
        'button_label' => 'Add Social',
        'min' => 1
    ]),
    wp_acf_field('Logo', 'image', [
        'ref' => 'footer_logo',
        'required' => 1
    ], '50'),
    wp_acf_field('Menu', 'select', [
        'ref' => 'footer_menu',
        'choices' => $menu_items,
        'required' => 1
    ], '50'),
    wp_acf_field('Address', 'textarea', [
        'rows' => 2,
        'new_lines' => 'br'
    ], '33.33'),
    wp_acf_field('Phone', 'text', [
        'ref' => 'footer_phone',
    ], '33.33'),
    wp_acf_field('Email', 'email', [], '33.33'),
    wp_acf_field('Copyright Text', 'textarea', [
        'rows' => 3,
        'instructions' => 'Use <code>{year}</code> to show the current year.',
        'default_value' => '© {year} Company Name'
    ])
], [ 'parent' => 'site-options' ]);