<?php 
    $render = function ($data) {
        $items = get_field('card_items');

        // Bail if there are no items.
        if (empty($items)) {
            echo 'No Items to Show';
            return;
        }
?>
    <div class="wrapper">
        <div class="title-area">
            <?php if (get_field('overline')) : ?>
                <div class="overline light appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
            <?php endif; ?>
            <?php component('headline', get_field('heading'), 'heading-2 appear--fade-in-up', ['data-appear' => '20']); ?>
        </div>
        <div class="card-items">
            <?php foreach ($items as $item) : ?>
                <div class="item appear--fade-in-up" data-appear="20">
                    <div class="head appear--fade-in-up" data-appear-child="40">
                        <?php if ($item['icon']) : ?>
                            <span class="icon">
                                <span class="cover">
                                    <?php print_svg($item['icon']); ?>
                                </span>
                            </span>
                        <?php endif ?>
                        <h4 class="heading heading-5 appear--fade-in-up" data-appear-child="40"><?= $item['title']; ?></h4>
                    </div>
                    <?php if ($item['text']) : ?>
                        <div class="text"><?= $item['text']; ?></div>
                    <?php endif ?>
                    <?php 
                        $btn = $item['button'];
                        $btn['icon'] = 'arrow';

                        component('button', $btn, 'appear--fade-in-up', ['data-appear-child' => '40']);
                    ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Card Items', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Icon', 'select', [
                'choices' => $icon_list
            ]),
            wp_acf_field('Title', 'text', [
                'required' => 1
            ]),
            wp_acf_field('Text', 'textarea'),
            wp_acf_field('Button', 'button'),
        ],
        'button_label' => 'Add New Card',
        'min' => 1,
        'max' => 3
    ])
];

wp_register_custom_block([
    'title' => 'Cards Grid',
    'icon' => 'grid-view',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);