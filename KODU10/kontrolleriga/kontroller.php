<?php
session_start();
require_once('pildid.php');


$mode = 'pealeht';

if (!empty($_GET)) {
    if ($_GET["mode"] != "") {
        $mode = $_GET["mode"];
    }
}

switch ($mode) {
    case 'galerii':
        require_once('head.html');
        include('galerii.php');
        break;
    case 'tulemus':
        require_once('head.html');
        include('tulemus.php');
        break;
    case 'vote':
        require_once('head.html');

        if(!empty($_SESSION["voted_for"])){
            header("Location: ?mode=tulemus");
            exit(0);
        } else {
            include("vote.php");
        }
        break;
    case 'logiv2lja':
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-40000, '/');
        }
        session_destroy();
        header("Location: ?mode=pealeht");
        exit(0);
        break;
    default:
        include('head.html');
        require_once('pealeht.php');
}
require_once('foot.html'); ?>

