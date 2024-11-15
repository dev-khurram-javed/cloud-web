<?php 
    $render = function ($data) {
?>
    <div class="wrapper">
        <div class="text">
            <?php component('rich-text', ['text' => get_field('content')], 'appear--fade-in-up', ['data-appear' => '20']); ?>
        </div>
        <?php 
            $btn = get_field('button');
            $btn['icon'] = 'arrow';

            component('button', $btn, 'appear--fade-in-up', ['data-appear' => '20']);
        ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Content', 'wysiwyg', [
        'media_upload' => 1,
        'required' => 1
    ]),
    wp_acf_field('Button', 'button')
];

wp_register_custom_block([
    'title' => 'Text',
    'icon' => 'media-text',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);