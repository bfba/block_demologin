<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    'block/demologin:addinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);
