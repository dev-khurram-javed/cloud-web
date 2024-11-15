<?php
wp_register_component('Post Card', function($data) {
    if (empty($data['post'])) return;

    $fields = $data['fields'];
    $post = $data['post'];
    $post_id = $post->ID;

    $taxonomies = get_post_taxonomies($post_id);
    if (!empty($taxonomies)) {
        $terms = get_the_terms($post_id, $taxonomies[0]);
    }
?>
    <article class="post">
        <div class="content">
            <?php if ($fields['show_category'] || $fields['show_date']) : ?>
                <div class="infos appear--fade-in-up" data-appear="20">
                    <?php if (isset($terms) && $terms && $fields['show_category']) : ?>
                        <span class="category"><strong><?= $terms[0]->name; ?></strong></span>
                    <?php endif ?>
                    <?php if ($fields['show_date']) : ?>
                        <span class="date">Published on <?= date('M j, Y', strtotime($post->post_date)); ?></span>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <h3 class="title heading-4 appear--fade-in-up" data-appear="20">
                <a href="<?= get_the_permalink($post_id); ?>"><?= $post->post_title ?></a>
            </h3>
            
            <?php if ($post->post_type == 'case-study') : ?>
                <?php if (!empty(get_field('project_roles', $post_id)) && $fields['show_excerpt']) : ?>
                    <div class="role-list appear--fade-in-up" data-appear="20">
                        <strong class="title">Our Role</strong>
                        <ul>
                            <?php 
                                foreach ((get_field('project_roles', $post_id)) as $key => $role) :
                                    if ($key < 4) :
                            ?>
                                <li><?= $role['role'] ?></li>
                            <?php 
                                    endif;
                                endforeach 
                            ?>
                        </ul>
                    </div>
                <?php endif ?>
            <?php else: ?>
                <?php if ($post->post_excerpt && $fields['show_excerpt']) : ?>
                    <p class="text appear--fade-in-up" data-appear="20"><?= $post->post_excerpt; ?></p>
                <?php endif ?>
            <?php endif ?>

            <?php 
                component('button', [
                    'link' => [
                        'title' => 'View More', 
                        'url' => get_the_permalink($post_id)
                    ], 
                    'icon' => 'arrow'
                ], 'appear--fade-in-up', ['data-appear' => '20']); 
            ?>
        </div>
        <?php
            if (has_post_thumbnail($post_id) && $fields['show_image']) :
                component('image', [
                    'post_id' => $post_id,
                    'link' => [
                        'url' => get_the_permalink($post_id),
                        'title' => $post->post_title
                    ],
                ], 'appear--zoom-in', ['data-appear' => '20']);
            endif
        ?>
    </article>
<?php
}, [
    'post' => [],
    'fields' => []
]);