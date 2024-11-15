<?php 

wp_register_component('Footer', function($data) {
    $f_logo = get_field('footer_logo', 'option');
    $f_menu = get_field('footer_menu', 'option');
    $f_phone = get_field('footer_phone', 'option');
    $addr = get_field('address', 'option');
    $email = get_field('email', 'option');
    $social = get_field('social_media', 'option');
    $copy = get_field('copyright_text', 'option');

    $menu_items = get_menu_items($f_menu);
?>
    <footer id="footer">
        <div class="f-top">
            <div class="wrapper">
                <div class="logo-area">
                    <?php
                        $f_logo['link'] = [
                            'url' => home_url(),
                            'title' => get_bloginfo() . ". Homepage"
                        ];
                        component('image', $f_logo, 'f-logo'); 
                    ?>
                    <?php if ($social): ?>
                        <ul class="social-items">
                            <?php foreach ($social as $key => $item) : ?>
                                <li>
                                    <a href="<?= $item['link'] ?>">
                                        <span class="icon"><?php print_svg('social/' . $item['icon']); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="f-nav">
                    <?= get_nav_html($menu_items); ?>
                </div>
                <div class="info">
                    <?php if ($addr) : ?> <span class="addr"><?= $addr; ?></span> <?php endif; ?>
                    <?php if ($f_phone) : ?> <a href="tel:<?= $f_phone; ?>" class="phone"><?= $f_phone; ?></a> <?php endif; ?>
                    <?php if ($email) : ?> <a href="mailto:<?= $email; ?>" class="email"><?= $email; ?></a> <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($copy): ?>
            <div class="f-bottom">
                <div class="wrapper">
                    <span class="copy"><?= str_replace('{year}', date('Y'), $copy); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </footer>
<?php
}, []);