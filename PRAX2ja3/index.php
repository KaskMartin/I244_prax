<?php
require_once('functions.php');
connect_db();
session_start();

$mode = 'pealeht';

if (!empty($_GET)) {
    if ($_GET["mode"] != "") {
        $mode = htmlspecialchars($_GET["mode"]);
    }
};

switch ($mode) {
    case 'testid':
        kuva_testid();
        break;
    case 'kysimused':
        kuva_kysimused();
        break;
    case 'laekysimus':
        kuva_laekysimus();
        break;
    case 'logisisse':
        kuva_logisisse();
        break;
    case 'registreeru':
        kuva_registreeru();
        break;
    case 'vastused':{
        kuva_vastused();
        break;
    }
    case 'logiv2lja':{
        kuva_logiv2lja();
        break;
    }
    default:
        kuva_pealeht();
};
?>