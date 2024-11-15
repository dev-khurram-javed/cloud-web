<?php
wp_register_component('Header', function($data) {
    $logo = get_field('logo', 'option');
    $phone = get_field('phone', 'option');
    $nav_menu = get_field('menu', 'option');
?>
    <header id="header">
        <div class="wrapper">
            <div class="inner">
                <?php
                    $logo['link'] = [
                        'url' => home_url(),
                        'title' => get_bloginfo() . ". Homepage"
                    ];
                    component('image', $logo, 'logo');

                    // Navigation
                    component('navigation', ['menu' => $nav_menu]); 
                ?>
                <div class="info">
                    <?php if ($phone): ?>
                        <span class="phone link">
                            <span class="icon"><?php print_svg('phone'); ?></span>
                            <a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
                        </span>
                    <?php endif; ?>
                </div>
                <?php 
                    component('mobile-menu-toggler', [
                        'open_icon' => 'hamburger',
                        'close_icon' => 'close'
                    ]); 
                ?>
            </div>
        </div>
        <!-- Mobile Nav -->
        <?php
            $add_content = '';

            if ($phone) :
                $add_content .= '<span class="phone link">';
                $add_content .= '<span class="icon"> ' . print_svg('phone', false) . '</span>';
                $add_content .= '<a href="tel:' . $phone . '">' . $phone . '</a>';
                $add_content .= '</span>';
            endif;
            
            component('mobile-nav', [
                'menu' => $nav_menu,
                'add_content' => $add_content
            ]);
        ?>
    </header>
<?php
}, []);