<?php 
    $render = function ($data) {
        $archive = wp_get_archive_data();
        $data['fields'] = (empty($data['fields'])) ? get_fields() : $data['fields'];

        if (!$archive) {
            echo 'Archive Support is not configured properly. Please check the sidebar.';
            return;
        }

        $archive['query_params']['orderby'] = get_field('order_by') ?: 'date';
        $archive['query_params']['order'] = get_field('order') ?: 'ASC';

        $query = new WP_Query($archive['query_params']);

        $posts = $query->posts;
        
        if (empty($posts)) {
            echo 'Please Select Posts to display';
            return;
        }

        $total_posts = $query->found_posts;
        $posts_per_page = $archive['query_params']['posts_per_page'];

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
    wp_acf_field('Automatic', 'message', [
        'message' => 'Block will pull Posts Automatically, Enable Archive Support for this Page and Select approprtiate options.',
    ]),
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