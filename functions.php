<?php
date_default_timezone_set("Europe/Moscow");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$config = require 'config.php';

$db = new mysqli($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['dbname']);
$db->set_charset($config['db']['charset']);
