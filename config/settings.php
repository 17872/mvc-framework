<?php

require_once 'settings-local.php';

define(
    'SETTINGS',
    serialize(
        array_merge(
            [

            ],
            unserialize(SETTINGS_LOCAL)
        )
    )
);