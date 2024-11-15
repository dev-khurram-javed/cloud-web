<?php 
    $render = function ($data) {
        $bg_style = (get_field('background_style')) ? get_field('background_style') : 'light';
        $img_pos = (get_field('image_position')) ? get_field('image_position') : 'right';

        add_block_class('bg-' . $bg_style, 'text-image');
?>
    <div class="wrapper <?= 'img-' . $img_pos; ?>">
        <div class="content">
            <?php component('headline', get_field('title'), 'heading-3 appear--fade-in-up', ['data-appear' => '20']); ?>
            <div class="desc appear--fade-in-up" data-appear="20">
                <?php component('rich-text', ['text' => get_field('description')]) ?>
            </div>
            <?php component('button', get_field('button'), 'appear--fade-in-up', ['data-appear' => '20']); ?>
        </div>
        <?php if (get_field('image')) : ?>
            <div class="img-wrap">
                <?php
                    $img = get_field('image');
                    $img['max_size'] = 'medium';

                    component('image', get_field('image'), 'appear--zoom-in', ['data-appear' => '20']); 
                ?>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Background Style', 'button_group', [
        'choices' => [
            'dark' => 'Dark',
            'light' => 'Light'
        ],
        'default_value' => 'light'
    ]),
    wp_acf_field('Image Position', 'button_group', [
        'choices' => [
            'left' => 'Left',
            'right' => 'Right'
        ],
        'default_value' => 'right'
    ]),
    wp_acf_field('Image', 'image'),
    wp_acf_field('Title', 'headline'),
    wp_acf_field('Description', 'wysiwyg'),
    wp_acf_field('Button', 'button')
];

wp_register_custom_block([
    'title' => 'Text Image',
    'icon' => 'text',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);