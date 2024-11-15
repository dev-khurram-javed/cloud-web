<?php 
    $render = function ($data) {
        $post_types = get_field('post_types');
        $posts_per_page = get_field('posts_per_page') ?: 3;
        $orderby = get_field('order_by') ?: 'date';
        $order = get_field('order') ?: 'ASC';
        $search = (isset($_GET['search_term']) && $_GET['search_term']) ? $_GET['search_term'] : '';

        $query_args = array(
            'post_type' => $post_types,
            'posts_per_page' => $posts_per_page,
            'orderby' => $orderby,
            'order' => $order,
            'paged' => get_query_var('paged'),
            's' => $search
        );

        // Check if Archive Page
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $taxonomy = get_taxonomy($term->taxonomy);

            $query_args['post_type'] = $taxonomy->object_type;
            $query_args['tax_query'] = [
                [
                    'taxonomy' => $term->taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug,
                ],
            ];
        }

        $query = new WP_Query($query_args);

        $posts = $query->posts;
        
        if (empty($posts)) {
            echo 'Please Select Posts to display';
            return;
        }

        $total_posts = $query->found_posts;

        $filters = [];
        $tax_obj = get_object_taxonomies( get_field('post_types'), 'objects' );
        
        foreach ($tax_obj as $tax) {
            $terms = get_terms([
                'taxonomy' => $tax->name,
                'hide_empty' => true,
            ]);

            if (empty($terms)) {
                continue;
            }

            $filters[$tax->name]['options'] = array_map(function ($term) use ($tax) {
                return [
                    'label' => $term->name,
                    'value' => get_term_link($term->term_id, $tax->name),
                ];
            }, $terms);

            $filters[$tax->name]['label'] = $tax->label;
        }

        $show_filters = (get_field('show_filters') !== null) ? get_field('show_filters') : true;
        $show_search = (get_field('show_search') !== null) ? get_field('show_search') : true;
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
        <?php if ($show_filters || $show_search) : ?>
            <div class="header">
                <?php if ($show_search) : ?>
                    <form class="search">
                        <input type="hidden" name="paged" value="1"/><!-- Important to reset the pagination -->
                        <div class="field">
                            <label class="sr-only" for="search_term">Use the search below to filter results.</label>
                            <input type="search" id="search_term" name="search_term" placeholder="Search ..." value="<?= $_GET['search_term'] ?? '' ?>"/>
                            <span class="line" aria-hidden="true"></span>
                        </div>
                        <button type="submit" class="submit" aria-label="Submit search">
                            <?php print_svg('search'); ?>
                        </button>
                    </form>
                <?php endif; ?>
                <?php 
                    if ($show_filters) {
                        foreach ($filters as $key => $filter) {
                            $filter['placeholder_link'] = home_url('case-studies');
                            component('custom-dropdown', $filter);
                        }
                    }
                ?>
            </div>
        <?php endif; ?>
        <div class="posts">
            <?php
                foreach ($posts as $post) :
                    component('post-card', ['post' => $post, 'fields' => $data['fields']]);
                endforeach; 
            ?>
        </div>
        <?php 
            if (get_field('pagination_type') !== 'load') {
                component('pagination', [ 'total_posts' => $total_posts, 'posts_per_page' => $posts_per_page ]); 
            }else {
                if ($posts_per_page < $total_posts) {
                    component('button', [
                        'type' => 'button', 
                        'icon' => 'arrow',
                        'style' => 'secondary',
                        'link' => [
                            'title' => 'Load More', 
                            'url' => '#'
                        ]
                    ], 'js-load-btn', [
                        'data-queryargs' => htmlspecialchars(json_encode($query_args)),
                        'data-offset' => $posts_per_page,
                        'data-totalPosts' => $total_posts
                    ]);
                }
            }
        ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Overline', 'text'),
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Post Types', 'select', [
        'choices' => $public_post_types,
        'multiple' => 1,
        'instructions' => 'Select the post types you want to display. (Hold ctrl or cmd to select multiple options.)',
    ]),
    wp_acf_field('Posts Per Page', 'number', [
        'min' => 1,
        'default_value' => 9
    ], '33.33%'),
    wp_acf_field('Pagination Type', 'button_group', [
        'choices' => [
            'pagination' => 'Number Pagination',
            'load' => 'Load More'
        ],
        'default_value' => 'pagination'
    ]),
    wp_acf_field('Order', 'button_group', [
        'choices' => [
            'ASC' => 'Ascending',
            'DESC' => 'Descending'
        ],
        'default_value' => 'ASC',
    ], '33.33%'),
    wp_acf_field('Order By', 'select', [
        'choices' => [
            'date' => 'Date',
            'title' => 'Title',
            'ID' => 'ID'
        ]
    ], '33.33%'),
    wp_acf_field('Show Search', 'true_false', [
        'default_value' => true
    ], '25%'), 
    wp_acf_field('Show Filters', 'true_false', [
        'default_value' => true
    ], '25%'), 
    wp_acf_field('Show Post Image', 'true_false', [
        'ref' => 'show_image',
        'default_value' => true
    ], '25%'), 
    wp_acf_field('Show Post Category', 'true_false', [
        'ref' => 'show_category',
        'default_value' => true
    ], '25%'),
    wp_acf_field('Show Post Date', 'true_false', [
        'ref' => 'show_date',
        'default_value' => true
    ], '25%'),
    wp_acf_field('Show Post Excerpt', 'true_false', [
        'ref' => 'show_excerpt'
    ], '25%')
];

wp_register_custom_block([
    'title' => 'Listing Grid',
    'icon' => 'grid-view',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);