<?php
include('functions.php');
alusta_sessioon();

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
    case 'logiv2lja':{
        kuva_logiv2lja();
        break;
    }
    default:
        kuva_pealeht();
};
?>