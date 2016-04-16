<?php
require_once('head.html');
require_once('pildid.php');
$mode = 'pealeht';

if (!empty($_GET)) {
    if ($_GET["mode"] != "") {
        $mode = $_GET["mode"];
    }
}

switch ($mode) {
    case 'galerii':
        require_once('galerii.php');
        break;
    case 'tulemus':
        require_once('tulemus.php');
        break;
    case 'vote':
        require_once('vote.php');
        break;
    default:
        require_once('pealeht.php');
}
require_once('foot.html'); ?>

