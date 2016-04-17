<?php
include('functions.php');

$mode = 'pealeht';

if (!empty($_GET)) {
    if ($_GET["mode"] != "") {
        $mode = $_GET["mode"];
    }
};

switch ($mode) {
    case 'galerii':
        kuva_galerii();
        break;
    case 'laefail':
        kuva_laefail();
        break;
    case 'logisisse':
        kuva_logisisse();
        break;
    case 'registreeru':
        kuva_registreeru();
        break;
    case 'pilt':{
        kuva_pilt();
        break;
    }
    default:
        kuva_pealeht();
};
?>