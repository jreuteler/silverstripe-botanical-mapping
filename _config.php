<?php

global $project;
$project = 'silverstripe-botanical-mapping';

global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

// Set the site locale
i18n::set_locale('en_US');