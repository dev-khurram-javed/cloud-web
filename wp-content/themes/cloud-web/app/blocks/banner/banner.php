<?php 
    $render = function ($data) {
        global $post;
        $content_type = (get_field('content_type') !== null) ? get_field('content_type') : 'custom';
        
        $bg = ($content_type == 'custom') ? get_field('background') : ['post_id' => $post->ID];
        $title = ($content_type == 'custom') ? get_field('title') : [ 'text' => get_the_title() ];
        $excerpt = ($content_type == 'custom') ? get_field('text') : get_the_excerpt();
?>
    <?php if ($bg) : ?>
        <div class="background">
            <?php component('image', $bg); ?>
            <div class="overlay"></div>
        </div>
    <?php endif; ?>
    <div class="wrapper">
        <div class="content">
            <?php 
                component('headline', $title, 'heading-2 appear--fade-in-up', ['data-appear' => '20']);  

                if ($excerpt) : 
            ?>
                <div class="text appear--fade-in-up" data-appear="20">
                    <p><?= $excerpt; ?></p>
                </div>
            <?php 
                endif;

                $btn = get_field('button');
                if (!empty($btn)) :
                    $btn['icon'] = 'arrow';
                    component('button', $btn, 'appear--fade-in-up', ['data-appear' => '20']);
                endif; 
            ?>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Content Type', 'button_group', [
        'choices' => [
            'auto' => 'Automatic',
            'custom' => 'Custom'
        ],
        'default_value' => 'custom'
    ]),
    wp_acf_field('Automatic', 'message', [
        'message' => 'Block will be Automatically populated with Post <strong>Title</strong>, <strong>Excerpt</strong> & <strong>Featured image</strong>',
        'show_if' => [
            'field' => 'content_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ]),
    wp_acf_field('Background', 'image', [
        'required' => 1,
        'show_if' => [
            'field' => 'content_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ]),
    wp_acf_field('Title', 'headline', [
        'required' => 1,
        'show_if' => [
            'field' => 'content_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ]),
    wp_acf_field('Text', 'textarea', [
        'show_if' => [
            'field' => 'content_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ]),
    wp_acf_field('Button', 'button', [
        'show_if' => [
            'field' => 'content_type',
            'operator' => '==',
            'value' => 'custom'
        ]
    ])
];

wp_register_custom_block([
    'title' => 'Banner',
    'icon' => 'slides',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);