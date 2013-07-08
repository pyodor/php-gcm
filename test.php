<?php
require_once 'vendor/autoload.php';

use Tarsier\Gcm;

$gcm = new Gcm('AIzaSyDBda_14UCnHi2XYtuob8a6InaRjx5IRu0');
$gcm->sendNotification(1, array('test' => 'test message'));

print_r($gcm->getResponse());
