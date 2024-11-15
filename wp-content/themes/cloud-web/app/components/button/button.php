<?php

wp_register_component('Button', function($data) {
    if (empty($data['link']) && empty($data['post'])) return;

    $tag_name = $data['type'] === 'link' ? 'a' : 'button';

    $button_label = (!empty($data['link'])) ? $data['link']['title'] : $data['post']->post_title;
    $button_url = (!empty($data['link'])) ? $data['link']['url'] : get_permalink($data['post']->ID);

    $attr = 'class="style-' . $data['style'] .'"';
    $attr .= ($tag_name == 'a') ? ' href="' . $button_url . '"' : '';
    $attr .= ($tag_name == 'a' && isset($data['link']['target'])) ? ' target="' . $data['link']['target'] . '"' : '';

    if (isset($data['link']['target']) && $data['link']['target'] == '_blank') {
        $attr .= ' rel="noopener noreferrer"';
    }

    // Custom Label
    $button_label = (!empty($data['custom_label'])) ? $data['custom_label'] : $button_label;

    printf('<%s %s>', $tag_name, $attr);
?>
    <span class="button-wrapper">
        <?php if ($button_label) : ?>
            <strong class="button-text"><?= $button_label; ?></strong>
        <?php endif; ?>
        <?php if ($data['icon']) : ?>
            <span class="button-icon" aria-hidden="true">
                <?php print_svg($data['icon']); ?>
            </span>
        <?php endif; ?>
    </span>
<?php
    printf('</%s>', $tag_name);
}, [
    'type' => 'link', 
    'link' => ['url' => '#'], 
    'icon' => null, 
    'style' => 'primary'
]);