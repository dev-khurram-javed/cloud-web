<?php 
    $render = function ($data) {
        $img_pos = (get_field('image_position')) ? get_field('image_position') : 'left';
?>
    <div class="wrapper <?= 'img-' . $img_pos; ?>">
        <div class="content">
            <div class="title-area">
                <?php if (get_field('overline')) : ?>
                    <div class="overline appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
                <?php endif; ?>
                <?php component('headline', get_field('title'), 'heading-2 appear--fade-in-up', ['data-appear'=>'20']); ?>
            </div>
            <?php if (get_field('text')) : ?>
                <div class="desc appear--fade-in-up" data-appear="20"><?= get_field('text'); ?></div>
            <?php endif; ?>
            <?php 
                $btn = get_field('button');
                $btn['icon'] = 'arrow';

                component('button', $btn, 'appear--fade-in-up', ['data-appear'=>'20']); 
            ?>
        </div>
        <?php if (get_field('image_1')) : ?>
            <div class="img-wrap">
                <?php
                    component('image', get_field('image_1'), 'image-1 appear--zoom-in', ['data-appear'=>'20']);
                    component('image', get_field('image_2'), 'image-2 appear--zoom-in', ['data-appear'=>'20']); 
                ?>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Image Position', 'button_group', [
        'choices' => [
            'left' => 'Left',
            'right' => 'Right'
        ],
        'default_value' => 'left'
    ]),
    wp_acf_field('Image 1', 'image'),
    wp_acf_field('Image 2', 'image'),
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Title', 'headline'),
    wp_acf_field('Text', 'wysiwyg'),
    wp_acf_field('Button', 'button')
];

wp_register_custom_block([
    'title' => 'Text Image 2',
    'icon' => 'columns',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);