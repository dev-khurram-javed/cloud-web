<?php
require_once 'custom-acf-fields.php';
require_once 'assemble-acf-field.php';
require_once 'register-acf-fields.php';

if (file_exists(APP_PATH . '/custom-fields.php')) {
    require_once APP_PATH . '/custom-fields.php';
}