<?php

/**
 * @copyright Copyright © 2024 Semajo. All rights reserved.
 * @author    Juan Carlos Conde <juancarlosc@onetree.com>
 */

// phpcs:ignoreFile
define('BP', dirname(__DIR__));

define('VENDOR_PATH', BP . '/app/etc/vendor_path.php');

if (!file_exists(VENDOR_PATH)) {
    throw new \Exception(
        'We can\'t read some files that are required to run the PHP Console Tool. '
        . 'This usually means file permissions are set incorrectly.'
    );
}

$vendorDir = require VENDOR_PATH;
$vendorAutoload = BP . "/{$vendorDir}/autoload.php";

/* 'composer install' validation */
if (file_exists($vendorAutoload)) {
    $composerAutoloader = include $vendorAutoload;
} else {
    throw new \Exception(
        'Vendor autoload is not found. Please run \'composer install\' under application root directory.'
    );
}
