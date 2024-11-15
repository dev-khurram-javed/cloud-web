<?php 
    $render = function ($data) {
        $bg = get_field('background');
?>
    <div class="inner spacing-top-normal spacing-bottom-normal">
        <?php if ($bg) : ?>
            <div class="background">
                <?php component('image', $bg); ?>
                <div class="overlay"></div>
            </div>
        <?php endif; ?>
        <div class="wrapper">
            <?php component('headline', get_field('title'), 'appear--fade-in-up', ['data-appear'=>'20']) ?>
            <div class="text appear--fade-in-up" data-appear="20">
                <?= get_field('text') ?>
            </div>
            <?php 
                $btn = get_field('button');
                $btn['icon'] = 'arrow';

                component('button', $btn, 'appear--fade-in-up', ['data-appear'=>'20']);
            ?>
        </div>
    </div>
    <?php if (get_field('banner_text')) : ?>
        <div class="banner-text">
            <div class="wrapper appear--fade-in" data-appear="10">
                <?= get_field('banner_text'); ?>
            </div>
        </div>
    <?php endif; ?>
<?php
};

$fields = [
    wp_acf_field('Background', 'image'),
    wp_acf_field('Title', 'headline'),
    wp_acf_field('Text', 'textarea'),
    wp_acf_field('Button', 'button'),
    wp_acf_field('Banner Text', 'text'),
];

wp_register_custom_block([
    'title' => 'Hero Main',
    'icon' => 'align-full-width',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render
]);