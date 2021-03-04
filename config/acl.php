<?php

return [
    /*
     * ACL adapters:
     * - php
     */
    'adapter' => getenv('ACL_ADAPTER') ?: 'php',

    /*
     * PHP acl adapter
     */
    'php' => [
        'file' => getenv('ACL_PHP_RULES_FILE') ?: './config/acl_rules.php',
    ],
];
