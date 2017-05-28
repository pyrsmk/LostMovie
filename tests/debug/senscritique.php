<?php

require('../../vendor/autoload.php');
require('../vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
set_time_limit(0);

$senscritique = new LostMovie\SensCritique();

debug($senscritique->search('50-50 (2011)'));
