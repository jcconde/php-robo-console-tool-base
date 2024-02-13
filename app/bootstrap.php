<?php

/**
 * @copyright Copyright © 2024 Semajo. All rights reserved.
 * @author    Juan Carlos Conde <juancarlosc@onetree.com>
 */

error_reporting(E_ALL);
//ini_set('display_errors', 1);

// PHP version validation
if (!defined('PHP_VERSION_ID') || !(PHP_VERSION_ID >= 8000)) {
    if (PHP_SAPI == 'cli') {
        echo 'PHP Console Tool supports PHP 8.0.0 or later. ';
    }
    exit(1);
}

require_once __DIR__ . '/autoload.php';

date_default_timezone_set('UTC');
