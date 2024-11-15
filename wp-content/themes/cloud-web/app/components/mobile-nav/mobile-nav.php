<?php
wp_register_component('Mobile Nav', function($data) {
    if (!$data['menu']) return;

    $menu = $data['menu'];
    $menu_items = get_menu_items($menu);

    if (empty($menu_items)) return;
?>
    <div
        id="mobile-nav"
        class="js-mobile-nav"
        role="menu"
        aria-expanded="false"
        aria-labelledby="mobile-menu-toggler">
            <?= get_nav_html($menu_items, 'mobile'); ?>
            <? if ($data['add_content']) : ?>
                <div class="menu-footer"><?= $data['add_content']; ?></div>
            <? endif ?>
    </div>
<?php
}, [
    'menu' => '',
    'add_content' => ''
]);