<?php

require_once 'settings-local.php';

define(
    'SETTINGS',
    serialize(
        array_merge(
            [
                'db' => (object)[
                    'host' => '',
                    'port' => '',
                    'dbname' => '',
                    'user' => '',
                    'pass' => ''
                ]
            ],
            unserialize(SETTINGS_LOCAL)
        )
    )
);