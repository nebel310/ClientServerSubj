<?php
$uri = $_SERVER['REQUEST_URI'];
if (strpos($uri, '/api/') === 0) {
    require __DIR__ . '/api/index.php';
    return true;
}
return false;