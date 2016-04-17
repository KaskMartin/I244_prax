<?php
function kuva_galerii () {
    require_once('view/head.html');
    include('view/pildid.php');
    require_once('view/galerii.html');
    require_once('view/foot.html');
};

function kuva_laefail () {
    require_once('view/head.html');
    require_once('view/laefail.html');
    require_once('view/foot.html');
};

function kuva_logisisse () {

    if (!empty($_POST)) {
        $errorid = array ();

        if (empty($_POST["Kasutajanimi"])) {
            array_push($errorid, "Kasutajanimi puudu!");
        }

        if (empty($_POST["Parool"])) {
            array_push($errorid, "Parool puudu!");;
        }

        if (empty($errorid)) {
            header('Location: ?mode=pealeht');
            exit;
        }
    }

    require_once('view/head.html');
    require_once('view/logisisse.html');
    require_once('view/foot.html');
};

function kuva_registreeru () {
    require_once('view/head.html');
    require_once('view/registreeru.html');
    require_once('view/foot.html');
};

function kuva_pilt () {
    include('view/pildid.php');
    $id=0;
    global $pildid;

    if ($_GET['id']== "" || empty($_GET['id']) || !is_numeric($_GET['id'])) {$id=0;}
    else $id = $_GET['id'];
    foreach ($pildid as &$value) {
        if ($id == $value['id']) {
            $pilt =  $value;
        };
    };

    if ($id<2) {$eelmine = sizeof($pildid);}
    else $eelmine = $id-1;

    if ($id == sizeof($pildid)) {$jargmine = 1;}
    else $jargmine = $id+1;

    include ('view/pilt.html');
};

function kuva_pealeht () {
    require_once('view/head.html');
    require_once('view/pealeht.html');
    require_once('view/foot.html');
};
?>