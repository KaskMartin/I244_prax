<?php
include('view/pildid.php');
if (empty($_GET['id'])) {
    include_once('view/head.html');
}
$mode = 'pealeht';

if (!empty($_GET)) {
    if ($_GET["mode"] != "") {
        $mode = $_GET["mode"];
    }
}

$id=0;

switch ($mode) {
    case 'galerii':
        require_once('view/galerii.html');
        break;
    case 'laefail':
        require_once('view/laefail.html');
        break;
    case 'logisisse':
        require_once('view/logisisse.html');
        break;
    case 'registreeru':
        require_once('view/registreeru.html');
        break;
    case 'pilt':{
        if ($_GET['id']== "" || empty($_GET['id']) || !is_numeric($_GET['id'])) {$id=0;}
        else $id = $_GET['id'];
        foreach ($pildid as &$value) {
            if ($id == $value['id']) {
                $pilt =  $value;
            }
        }
        if ($id<2) {$eelmine = sizeof($pildid);}
        else $eelmine = $id-1;

        if ($id == sizeof($pildid)) {$jargmine = 1;}
        else $jargmine = $id+1;

        include ('view/pilt.html');
        break;
    }
    default:
        require_once('view/pealeht.html');
}

include_once('view/foot.html');
?>