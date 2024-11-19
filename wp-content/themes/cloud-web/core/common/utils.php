<?php

$icon_list = [
    '' => 'No Icon',
    'bullhorn' => 'Bullhorn',
    'bullseye' => 'Bullseye',
    'display-code' => 'Code',
    'cloud' => 'Cloud',
    'email' => 'Email',
    'phone' => 'Phone',
    'quotes' => 'Quotes',
];

// Generate Slug
function get_slug($str, $rep_str = '-') {
    if (!$str) return;

    // Remove all the Special Charachters
    $str = preg_replace('/[^A-Za-z0-9 ]/', '', $str);
    return str_replace(' ', $rep_str, strtolower($str));
}

function convert_to_singular ($label) {
    // Handle -ies to -y (e.g., "categories" to "category")
    if (substr($label, -3) === 'ies') {
        return substr($label, 0, -3) . 'y';
    }

    // Checks 's' and converts it to '' (e.g., "Books" to "Book")
    elseif (substr($label, -1) === 's' && $label != 'News') {
        return substr($label, 0, -1);
    }

    // Handle -ves to -f or -fe (e.g., "wolves" to "wolf")
    if (substr($label, -3) === 'ves') {
        return substr($label, 0, -3) . 'f'; // Use 'f' for generalization; 'fe' can be added for specific cases
    }
    
    // If none of the rules apply, return the original label
    return $label;
}

// SVG
function print_svg($path, $svg_echo = true, $replace_colors = true) {
    $file_path = get_stylesheet_directory() . '/app/assets/svgs/' . $path;
    $path = get_stylesheet_directory_uri() . '/app/assets/svgs/' . $path;

    if (!str_ends_with($path, '.svg')) {
        $path .= '.svg';
        $file_path .= '.svg';
    }

    if (!file_exists($file_path)) { return; }

    $svg = file_get_contents($path);

    if ($replace_colors) {
        $svg = preg_replace('/(fill|stroke)="#?\w+"/', '$1="currentColor"', $svg);
    }

    if ($svg_echo) { echo $svg; } else { return $svg; }
}

// Nav Menu Items
function get_menu_items($menu) {
    // Get the menu items.
    $menu_items = wp_get_nav_menu_items($menu);

    if (empty($menu_items)) {
        return [];
    }

    global $post;

    // Build the menu items.
    $items = [];

    foreach ($menu_items as $item) {
        if (!$menu) return null;

        $fields = get_fields($item->ID);

        $item_data = [
            'ID' => $item->ID,
            'title' => !empty($item->post_title) ? $item->post_title : $item->title,
            'url' => $item->url,
            'target' => $item->target,
            'data' => $fields,
            'is_active' => false
        ];

        // Add classes.
        $classes = array_filter($item->classes);

        if (!empty($classes)) {
            $item_data['classes'] = $classes;
        }

        // Set Active
        $item_data['is_active'] = ($item->object_id == get_the_ID($post)) ? true : false;

        // Check if the item has a parent.
        $parent = $item->menu_item_parent;

        if ($parent && isset($items[$parent])) {
            $items[$parent]['children'][] = $item_data;
        } else {
            $items[$item->ID] = $item_data;
        }
    }

    return array_values($items);
}

// Nav Menu HTML
function get_nav_html ($menu_items, $type='') {
    if (!is_array($menu_items) || empty($menu_items)) return;

    $ul_class = ($type == 'mobile') ? 'class="mobile-menu"' : '';
    
    $output = '';
    $output .= '<ul ' . $ul_class . '>';

    foreach ($menu_items as $item_index => $item) :
        $classes = (array_key_exists('classes', $item)) ? $item['classes'] : [];

        if ($item['is_active']) array_push($classes, 'active');
        if ($type == 'mobile') array_push($classes, 'js-item');

        $class_list = (!empty($classes)) ? implode(' ', $classes) : '';
        $target = (!empty($item['target'])) ? 'target="' . $item['target'] . '"' : '';
        $children = $item['children'] ?? '';

        $output .= '<li class="nav-item ' . $class_list .'" role="menuitem">';

        $output .= ($type == 'mobile') ? '<div class="item-toggle js-item-toggle">' : '';

        $output .= '<a href="' . $item['url'] .'" ' . $target .' class="nav-item-link">' . $item['title'] . '</a>';

        $output .= ($type == 'mobile' && $children) ? '<span class="icon-toggle js-icon-toggle">' . print_svg('arrow-slider', false) . '</span>' : '';
        $output .= ($type == 'mobile') ? '</div>' : '';
        
        if ($children) :
            $back_btn = '';

            if ($type == 'mobile') {
                $back_btn .= '<div class="btn-wrap">';
                $back_btn .= '<button class="back-btn js-back">';
                $back_btn .= '<span class="icon">' . print_svg('chevron', false) . '</span>'; 
                $back_btn .= '<span class="text">Back</span>';
                $back_btn .= '</button>';
                $back_btn .= '</div>'; 
            }

            $output .= '<div class="dropdown">';
            $output .= $back_btn;
            $output .= get_nav_html($children);
            $output .= '</div>'; 
        endif;

        $output .= '</li>';
    endforeach;

    $output .= '</ul>';

    return $output;
}

/**
 * Retrieves the full list of Gravity forms. Only includes the form ID and title.
 *
 * @return array
 */
function list_forms() {
    if (!class_exists('GFAPI')) return [];

    $forms = GFAPI::get_forms();
    $forms_list = [];

    foreach ($forms as $form) {
        $forms_list[$form['id']] = $form['title'];
    }

    return $forms_list;
}

// Get the List of public post types.
function list_post_types($exclude = []) {
    $core_excluded = ['post', 'mega-menu', 'attachment'];

    if(!empty($exclude)) {
        array_merge($core_excluded, $exclude);
    } else {
        $exclude = $core_excluded;
    }

    $post_types = get_post_types([
        'public' => true
    ]);

    $public_post_types = array_reduce($post_types, function ($carry, $item) use ($exclude) {
        // Exclude these post types from the archive feature.
        if (in_array($item, $exclude)) {
            return $carry;
        }
    
        return array_merge($carry, [$item => get_post_type_object($item)->label]);
    }, []);

    return $public_post_types;
}

// Get the List of public taxonomies.
function list_taxonomies($exclude = []) {
    $core_excluded = ['post', 'mega-menu', 'attachment'];

    if(!empty($exclude)) {
        array_merge($core_excluded, $exclude);
    } else {
        $exclude = $core_excluded;
    }

    $tax = get_taxonomies([
        'publicly_queryable' => true,
        'show_ui' => true,
    ]);

    $public_taxonomies = array_reduce($tax, function ($carry, $item) use ($exclude) {
        // Exclude these taxonomies from the archive feature.
        if (in_array($item, $exclude)) {
            return $carry;
        }

        return array_merge($carry, [$item => get_taxonomy($item)->label]);
    }, []);

    return $public_taxonomies;
}