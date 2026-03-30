<?php
// Redirect to public folder (padrão para apps que usam public/ como webroot)
$host = $_SERVER['HTTP_HOST'];
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
header('Location: http://' . $host . $base . '/public/');
exit;