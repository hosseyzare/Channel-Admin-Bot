<?php
use Symfony\Component\Dotenv\Dotenv;

require_once getcwd(). '/vendor/autoload.php';
require_once getcwd(). '/view/string.php';
require_once getcwd(). '/core/db.php';


$dotenv = new Dotenv();
$dotenv->load(getcwd() . '/.env');


