<?php 
define("CORE_PATH", get_template_directory().'/core');
define("APP_PATH", get_template_directory().'/app');
define("PUBLIC_PATH", get_template_directory().'/public');
define("PUBLIC_SRC", get_template_directory_uri().'/public');

$components = [];
$acf_custom_fields = [];

add_action( 'init', function() {
    require_once CORE_PATH . '/common/index.php';
    require_once CORE_PATH . '/acf/index.php';
    require_once CORE_PATH . '/taxonomies/index.php';
    require_once CORE_PATH . '/post-types/index.php';
    require_once CORE_PATH . '/acf/core-fields.php';
    require_once CORE_PATH . '/components/index.php';
    require_once CORE_PATH . '/blocks/index.php';
    require_once CORE_PATH . '/option-pages/index.php';
    
    require_once APP_PATH . '/theme-functions.php';
});

// Theme Setup
function theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
}

add_action( 'after_setup_theme', 'theme_setup' );