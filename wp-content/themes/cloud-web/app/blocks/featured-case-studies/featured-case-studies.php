<?php 
    $render = function ($data) {
        $posts = '';
        if (get_field('query_type') == 'auto') {
            $query_args = array (
                'post_type' => 'case-study',
				'posts_per_page' => 4
			);
            
            $posts = get_posts($query_args);
        }else {
            $posts = get_field('posts');
        }
        
        // Bail if there are no posts.
        if (empty($posts)) {
            echo 'No Posts to Show';
            return;
        }

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
                        <div class="overline appear--fade-in-up" data-appear="20"><?= get_field('overline'); ?></div>
                    <?php endif; ?>
                    <?php component('headline', get_field('heading'), 'heading-2 appear--fade-in-up', ['data-appear' => '20']); ?>
                </div>
                <?php if (get_field('text')) : ?> 
                    <div class="text appear--fade-in-up" data-appear="20"><?= get_field('text'); ?></div>
                <?php endif; ?>
                <?php 
                    $btn = get_field('button');
                    $btn['icon'] = 'arrow';

                    component('button', $btn, 'appear--fade-in-up', ['data-appear' => '20']); 
                ?>
            </div>
        <?php endif; ?>
        <div class="posts">
            <?php 
                foreach ($posts as $post) :
                    $link = get_the_permalink($post->ID);
                    $title = $post->post_title;
                    $link = $link;
                    $cta = [ 'url' => $link, 'title' => 'View More' ];
                    $image = has_post_thumbnail($post->ID) ? ['post_id' => $post->ID, 'link' => $cta] : '';
                    $cats = get_categories($post);
                    $date = get_the_date( 'm.d.y', $post );
            ?>
                <div class="post-item">
                    <?php if ($image) component('image', $image, 'appear--zoom-in', ['data-appear' => '20']); ?>
                    <div class="content">
                        <h3 class="title heading-6"><a href="<?= $link; ?>"><?= $title; ?></a></h3>
                        <?php component('button', ['link' => $cta, 'icon' => 'arrow']); ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Show Decorations?', 'true_false', [
        'default_value' => '1'
    ]),
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Text', 'textarea', [
        'new_lines' => 'br'
    ]),
    wp_acf_field('Button', 'button'),
    wp_acf_field('Query Type', 'button_group', [
        'choices' => [
            'auto' => 'Automatic',
            'choose' => 'Choose'
        ],
        'default_value' => 'auto'
    ]),
    wp_acf_field('Automatic', 'message', [
        'message' => 'Block will be Automatically populated with latest 4 <a href="' . home_url() . '/wp-admin/edit.php?post_type=case-study">Case Studies</a>',
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'auto'
        ]
    ]),
    wp_acf_field('Posts', 'relationship', [
        'post_type' => 'case-study',
        'filters' => ['search'],
        'min' => 1,
        'max' => 4,
        'show_if' => [
            'field' => 'query_type',
            'operator' => '==',
            'value' => 'choose'
        ]
    ])
];

wp_register_custom_block([
    'title' => 'Featured Case Studies',
    'icon' => 'align-pull-left',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);