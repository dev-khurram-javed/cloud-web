<?php

require_once 'register-option-pages.php';

if (file_exists(APP_PATH . '/option-pages.php')) {
    require_once APP_PATH . '/option-pages.php';
}