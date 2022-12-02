<?php

require_once '../parsedown/parsedown_extra.php';

$uri = $_SERVER['REQUEST_URI'];

$docRoot = __DIR__ . '/..';


// return README.md if no file is specified
if ($uri === '/' || strlen($uri) === 0) {
    $uri = '/README';
}

if (file_exists($file = $docRoot . $uri .'.md')) {
    $parsedown = new Parsedown();
    echo $parsedown->text(file_get_contents($file));
} else {
    echo '404 - file "' .$file. '" Not Found';
}
