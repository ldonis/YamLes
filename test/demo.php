<?php
error_reporting(E_ALL);ini_set("display_errors", 1);
include '../yamles.php';

echo '<pre>';
print_r(yamles::parser('demo'));
echo '</pre>';