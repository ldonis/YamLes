<?php
error_reporting(E_ALL);ini_set("display_errors", 1);
include '../yamles.php';
echo '<pre>';
$yml = yamles::parser('demo');
echo '<h2>YML Parser example</h2>';
print_r($yml);
echo '</pre>';