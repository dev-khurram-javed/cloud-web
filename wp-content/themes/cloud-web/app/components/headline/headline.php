<?php

wp_register_component('Headline', function($data) {

    if (!isset($data['text'])) return;

    $text = $data['text'];
    $text = preg_replace('/\*\*/', '<span>', $text, 1); // Replace the first occurrence with <span>
    $text = preg_replace('/\*\*(?!.*\*\*)/', '</span>', $text); // Replace the last occurrence with </span>

    printf('<%s>%s</%s>', $data['html_tag'], $text, $data['html_tag']);
}, [
    'html_tag' => 'h2',
    'text' => null
]);