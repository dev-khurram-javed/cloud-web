<?php 
    $render = function ($data) { 
        $form = get_field('form');
        $addr = get_field('address');
        $phone = get_field('phone');
        $email = get_field('email');
        $social = get_field('show_social_media');
?>
    <div class="wrapper">
        <?php if (!empty($addr) || !empty($phone) || !empty($email)) :  ?>
            <div class="info-area">
                <?php if (!empty($addr)) : ?>
                    <div class="info-item appear--fade-in-up" data-appear="20">
                        <strong class="title">Address</strong>
                        <span class="value"><?= $addr ?></span>
                    </div>
                    <span class="line"></span>
                <?php endif; ?>
                
                <?php if (!empty($phone)) : ?>
                    <div class="info-item appear--fade-in-up" data-appear="20">
                        <strong class="title">Phone</strong>
                        <a href="tel:<?= $phone ?>" class="value"><?= $phone ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($email)) : ?>
                    <span class="line"></span>
                    <div class="info-item appear--fade-in-up" data-appear="20">
                        <strong class="title">Email</strong>
                        <a href="mailto:<?= $email ?>" class="value"><?= $email ?></a>
                    </div>
                <?php endif; ?>

                <?php   
                    if (!empty($social)) : 
                        $social_items = get_field('social_media', 'option');
                ?>  
                    <span class="line"></span>
                    <div class="info-item appear--fade-in-up" data-appear="20">
                        <strong class="title">Follow Us</strong>
                        <ul class="social-items">
                            <?php foreach ($social_items as $key => $item) : ?>
                                <li>
                                    <a href="<?= $item['link'] ?>">
                                        <span class="icon"><?php print_svg('social/' . $item['icon']); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="form-area">
            <?php
                component('headline', get_field('heading'), 'heading-3 appear--fade-in-up', ['data-appear' => '20']);
                component('form', ['form_id' => $form['variation'], 'button_label' => $form['button_label']], 'appear--fade-in-up', ['data-appear' => '20']) 
            ?>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Address', 'text'),
    wp_acf_field('Phone', 'text'),
    wp_acf_field('Email', 'email'),
    wp_acf_field('Show Social Media', 'true_false', [
        'default_value' => '1'
    ]),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Form', 'form'),
];

wp_register_custom_block([
    'title' => 'Contact Form',
    'icon' => 'columns',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);