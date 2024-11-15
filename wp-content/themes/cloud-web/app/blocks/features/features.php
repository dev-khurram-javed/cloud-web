<?php 
    $render = function ($data) {
        $decor = (get_field('show_decorations') !== null) ? get_field('show_decorations') : true;
?>
    <?php if ($decor) : ?>
        <div class="decor">
            <div class="top"><?php print_svg('cloud'); ?></div>
            <div class="bottom"><?php print_svg('cloud'); ?></div>
        </div>
    <?php endif; ?>
    <div class="wrapper">
        <?php if (get_field('heading')) : ?>
            <div class="heading">
                <div class="title-area">
                    <?php if (get_field('overline')) : ?>
                        <div class="overline light appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
                    <?php endif; ?>
                    <?php component('headline', get_field('heading'), 'heading-2 appear--fade-in-up', ['data-appear' => '20']); ?>
                </div>
                <?php if (get_field('text')) : ?> 
                    <div class="text appear--fade-in-up" data-appear="20"><?= get_field('text'); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (get_field('features')) : ?>
            <div class="feats">
                <?php 
                    foreach (get_field('features') as $key => $feat) : 
                        $icon = (!empty($feat['icon'])) ? '<span class="icon appear--zoom-in" data-appear="20"><span class="cover">' . print_svg($feat['icon'], false) . '</span></span>' : '';
                ?>
                    <div class="col">
                        <?= $icon ?>
                        <h3 class="title heading-6 appear--fade-in-up" data-appear="20"><?= $feat['title'] ?></h3>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Show Decorations?', 'true_false', [
        'default_value' => '1'
    ]),
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Text', 'textarea'),
    wp_acf_field('Features', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Icon', 'select', [
                'choices' => $icon_list
            ]),
            wp_acf_field('Title', 'text')
        ],
        'min' => 1,
        'max' => 6,
        'button_label' => 'Add New Feature',
    ])
];

wp_register_custom_block([
    'title' => 'Features',
    'icon' => 'editor-ol',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);