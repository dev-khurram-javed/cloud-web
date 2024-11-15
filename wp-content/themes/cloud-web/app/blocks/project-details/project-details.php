<?php 
    $render = function ($data) {
        global $post;
        $post_id = $post->ID;
?>
    <div class="wrapper">
        <div class="inner">
            <?php if (get_field('title')) : ?>
                <h5 class="heading-5 title appear--fade-in-up" data-appear="20"><?= get_field('title') ?></h5>
            <?php endif ?>
            <?php component('rich-text', ['text' => get_field('text')], 'appear--fade-in-up', ['data-appear' => '20']); ?>
            <hr class="line appear--fade-in-up" data-appear="20">
            <div class="info">
                <?php if (get_field('client_name')) : ?>
                    <div class="col appear--fade-in-up" data-appear="20">
                        <strong class="title">Client:</strong>
                        <span class="value"><?= get_field('client_name') ?></span>
                    </div>
                <?php endif ?>
                <?php if (get_field('roles')) : ?>
                    <div class="col appear--fade-in-up" data-appear="20">
                        <strong class="title">Project Role:</strong>
                        <?php if (!empty(get_field('project_roles', $post_id))) : ?>
                            <ul>
                                <?php foreach ((get_field('project_roles', $post_id)) as $key => $role) : ?>
                                    <li><?= $role['role'] ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </div>
                <?php endif ?>
                <?php if (get_field('date')) : ?>
                    <div class="col appear--fade-in-up" data-appear="20">
                        <strong class="title">Date:</strong>
                        <span class="value"><?= get_field('date') ?></span>
                    </div>
                <?php endif ?>
                <?php if (get_field('project_link')) : ?>
                    <div class="col appear--fade-in-up" data-appear="20">
                        <?php component('button', ['link' => ['title' => 'Live Link', 'url' => get_field('project_link')], 'icon' => 'arrow']) ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
<?php
};

$fields = [
    wp_acf_field('Title', 'text'),
    wp_acf_field('Text', 'wysiwyg'),
    wp_acf_field('Client Name', 'text'),
    wp_acf_field('Show Project Roles', 'true_false', [
        'ref' => 'roles',
        'default_value' => '1'
    ]),
    wp_acf_field('Date', 'date_picker', [
        'display_format' => 'F j, Y',
		'return_format' => 'F j, Y',
    ]),
    wp_acf_field('Project Link', 'url')
];

wp_register_custom_block([
    'title' => 'Project Details',
    'icon' => 'align-wide',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);