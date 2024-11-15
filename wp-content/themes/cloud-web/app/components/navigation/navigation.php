<?php
wp_register_component('Navigation', function($data) {
    if (!$data['menu']) return;

    $menu = $data['menu'];
    $menu_items = get_menu_items($menu);

    if (empty($menu_items)) return;

    echo '<nav id="nav">' . get_nav_html($menu_items) . '</nav>';
}, [
    'menu' => ''
]);