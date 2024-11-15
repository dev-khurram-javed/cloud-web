<?php 
    $render = function ($data) {
        $bg_style = (get_field('background_style')) ? get_field('background_style') : 'light';
        $img_pos = (get_field('image_position')) ? get_field('image_position') : 'right';
        $animate = (get_field('animate_numbers') !== null) ? get_field('animate_numbers') : true;
        $animate_class = ($animate) ? ' animate-numbers' : '';
        $stats = get_field('stats');

        add_block_class('bg-' . $bg_style . $animate_class, 'stats');
?>
    <div class="wrapper <?= 'img-' . $img_pos; ?>">
        <div class="content">
            <div class="title-area">
                <?php if (get_field('overline')) : ?>
                    <div class="overline appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
                <?php endif; ?>
                <?php component('headline', get_field('heading'), 'heading-2 appear--fade-in-up', ['data-appear' => '20']); ?>
            </div>
            <div class="desc appear--fade-in-up" data-appear="20">
                <?php component('rich-text', ['text' => get_field('description')]) ?>
            </div>
            <?php if ($stats) : ?>
                <div class="stats">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="stat">
                            <strong class="title appear--fade-in-up" data-appear="20"><?= $stat['title']; ?></strong>
                            <div class="stat-value appear--fade-in-up" data-appear="20">
                                <span class="num" data-number="<?= $stat['number'] ?>"><?= $stat['number'] ?></span>
                                <span class="suf"><?= $stat['suffix'] ?></span>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif; ?>
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
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Description', 'wysiwyg'),
    wp_acf_field('Animate Numbers?', 'true_false', [
        'default_value' => '1'
    ]),
    wp_acf_field('Stats', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Title', 'text'),
            wp_acf_field('Number', 'number'),
            wp_acf_field('Suffix', 'text')
        ],
        'button_label' => 'Add New Stat',
        'min' => '1',
        'max' => '3',
    ])
];

wp_register_custom_block([
    'title' => 'Stats',
    'icon' => 'chart-bar',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);