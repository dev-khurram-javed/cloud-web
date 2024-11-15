<?php 
    $render = function ($data) {
        $media = get_field('media_items');

        if (empty($media)) {
            echo 'Please Select Media Items to display';
            return;
        }
?>
    <div class="wrapper">
        <?php if (isset(get_field('heading')['text']) || get_field('overline')) : ?>
            <div class="title-area">
                <?php if (get_field('overline')) : ?>
                    <div class="overline appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
                <?php endif; ?>
                <?php component('headline', get_field('heading'), 'heading-2 appear--fade-in-up', ['data-appear' => '20']); ?>
            </div>
        <?php endif; ?>
        <div class="gallery-wrapper">
            <div class="controls">
                <div class="swiper-indicator js-indicator appear--fade-in-up" data-appear="20"></div>
                <div class="swiper-controls appear--fade-in-up" data-appear="20">
                    <button class="prev js-prev"><?php print_svg('arrow') ?></button>
                    <button class="next js-next"><?php print_svg('arrow') ?></button>
                </div>
            </div>

            <div class="swiper slider-images js-slider-images appear--fade-in-up" data-appear="20">
                <div class="swiper-wrapper">
                    <?php
                        foreach ($media as $item) :
                    ?>
                        <div class="swiper-slide slide">
                            <?php 
                                if ($item['type'] == 'image') :
                                    component('image', $item['image']);
                                else:
                                    component('video', $item['video']);
                                endif;
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Media Items', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Type', 'button_group', [
                'choices' => [
                    'image' => 'Image',
                    'video' => 'Video'
                ]
            ]),
            wp_acf_field('Image', 'image', [
                'show_if' => [
                    'field' => 'type',
                    'operator' => '==',
                    'value' => 'image'
                ]
            ]),
            wp_acf_field('Video', 'video', [
                'show_if' => [
                    'field' => 'type',
                    'operator' => '==',
                    'value' => 'video'
                ]
            ])
        ],
        'button_label' => 'Add New Item'
    ]) 
];

wp_register_custom_block([
    'title' => 'Media Gallery',
    'icon' => 'format-gallery',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);