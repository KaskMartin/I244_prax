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