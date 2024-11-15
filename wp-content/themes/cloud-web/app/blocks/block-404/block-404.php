<?php 
    $render = function ($data) {
?>
    <div class="decor">
        <div class="top appear--zoom-in" data-appear="20"><?php print_svg('cloud'); ?></div>
        <div class="bottom appear--zoom-in" data-appear="20"><?php print_svg('cloud'); ?></div>
    </div>
    <div class="wrapper">
        <?php 
            component('headline', get_field('heading'), 'appear--fade-in-up', ['data-appear' => '20']);

            if (get_field('text')) : 
        ?>
            <div class="text appear--fade-in-up" data-appear="20"><?= get_field('text') ?></div>
        <?php 
            endif;

            $btn = get_field('button');
            $btn['icon'] = 'arrow';

            component('button', $btn, 'appear--fade-in-up', ['data-appear' => '20']);
        ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Text', 'textarea', [
        'new_lines' => 'br'
    ]),
    wp_acf_field('Button', 'button')
];

wp_register_custom_block([
    'title' => 'Block 404',
    'icon' => 'warning',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);