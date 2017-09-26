<?php

$GLOBALS['config'] = [
    'mysql' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'name' => 'main1'
    ]
];

spl_autoload_register(function($class) {
	require_once "core/{$class}.php";
});
