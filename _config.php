<?php

global $project;
$project = 'silverstripe-botanical-mapping';

global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

// Set the site locale
i18n::set_locale('en_US');
Config::inst()->update('i18n', 'date_format', 'dd.MM.YYYY');
Config::inst()->update('i18n', 'time_format', 'HH:mm');


define('BOTANICALMAPPING_DIR', basename(dirname(__FILE__)));