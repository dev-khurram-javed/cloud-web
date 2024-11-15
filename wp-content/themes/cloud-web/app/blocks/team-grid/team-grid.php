<?php 
    $render = function ($data) {
?>
    <div class="wrapper">
        <div class="heading">
            <div class="title-area">
                <?php if (get_field('overline')) : ?>
                    <div class="overline appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
                <?php endif; ?>
                <?php component('headline', get_field('heading'), 'heading-2 appear--fade-in-up', ['data-appear' => '20']); ?>
            </div>
            <div class="swiper-controls appear--fade-in-up" data-appear="20">
                <button class="prev js-prev"><?php print_svg('arrow') ?></button>
                <button class="next js-next"><?php print_svg('arrow') ?></button>
            </div>
        </div>
        <?php if (get_field('team_members')) : ?>
            <div class="team-members">
                <div class="swiper slider-carousel">
                    <div class="swiper-wrapper">
                        <?php foreach (get_field('team_members') as $member) : ?>
                            <div class="team-card swiper-slide slide">
                                <?php component('image', $member['image'], 'appear--zoom-in', ['data-appear' => '20']); ?>
                                <div class="info">
                                    <h3 class="name appear--fade-in-up" data-appear="20"><?= $member['name']; ?></h3>
                                    <span class="title appear--fade-in-up" data-appear="20"><?= $member['title']; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Team Members', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Image', 'image'),
            wp_acf_field('Name', 'text', [
                'required' => 1
            ]),
            wp_acf_field('Title', 'text', [
                'required' => 1
            ]),
            wp_acf_field('Email', 'email'),
            wp_acf_field('Bio', 'wysiwyg')
        ],
        'button_label' => 'Add New Member',
        'min' => 1,
    ])
];

wp_register_custom_block([
    'title' => 'Team Grid',
    'icon' => 'groups',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);