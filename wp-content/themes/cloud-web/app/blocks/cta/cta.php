<?php 
    $render = function ($data) {
        $bg = get_field('background');
?>
    <?php if ($bg) : ?>
        <div class="background">
            <?php component('image', $bg); ?>
            <div class="overlay"></div>
        </div>
    <?php endif; ?>
    <div class="wrapper">
        <div class="title-area">
            <?php if (get_field('overline')) : ?>
                <div class="overline light appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
            <?php endif; ?>
            <?php component('headline', get_field('heading'), 'heading-2 appear--fade-in-up', ['data-appear' => '20']); ?>
        </div>
        <?php if (get_field('text')) : ?>
            <div class="text appear--fade-in-up" data-appear="20"><?= get_field('text') ?></div>
        <?php endif; ?>
        <?php 
            $btn = get_field('button');
            $btn['icon'] = 'arrow';

            component('button', $btn, 'appear--fade-in-up', ['data-appear' => '20']);
        ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Background', 'image'),
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Text', 'textarea'),
    wp_acf_field('Button', 'button')
];

wp_register_custom_block([
    'title' => 'CTA',
    'icon' => 'align-center',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);