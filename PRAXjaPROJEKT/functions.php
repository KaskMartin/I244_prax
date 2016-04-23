<?php
$_SESSION['logitud']="";

function alusta_sessioon(){
    ini_set("session.cookie_lifetime",30*60);
    session_start();
}

function lopeta_sessioon(){
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
}

function kuva_galerii () {
    require_once('view/head.php');
    include('view/pildid.php');
    require_once('view/galerii.html');
    require_once('view/foot.html');
};

function kuva_laefail () {
    if (empty($_SESSION['logitud']) || $_SESSION['logitud'] != 'true' ) {
        $_SESSION['logimisteade']="Vabandame, kuid pilte lisada saavad ainult registreerunud kasutajad.";
        header('Location: ?mode=galerii');
        exit(0);
    }
    require_once('view/head.php');
    require_once('view/laefail.html');
    require_once('view/foot.html');
};

function kuva_logisisse () {
    $errorid = array ();

    if (!empty($_POST)) {

        if (empty($_POST["Kasutajanimi"])) {
            array_push($errorid, "Kasutajanimi puudu!");
        }

        if ($_POST["Kasutajanimi"] != 'kasutaja') {
            array_push($errorid, "Vale kasutaja nimi!");
        }

        if (empty($_POST["Parool"])) {
            array_push($errorid, "Parool puudu!");
        }

        if ($_POST["Parool"] != 'parool') {
            array_push($errorid, "Vale parool!");
        }

        if (empty($errorid)) {
            $_SESSION['logitud']='true';
            $_SESSION['logimisteade']='Oled edukalt sisse loginud!';
            header('Location: ?mode=galerii');
            exit(0);
        }
    }

    require_once('view/head.php');
    if (!empty($errorid)) {
        echo implode("</br>", $errorid);
        echo "</br>";
    }
    require_once('view/logisisse.html');
    require_once('view/foot.html');
};

function kuva_registreeru () {
    require_once('view/head.php');
    require_once('view/registreeru.html');
    require_once('view/foot.html');
};

function kuva_logiv2lja() {
    lopeta_sessioon();
    header('Location: ?mode=ElPHPant');
    exit(0);
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
    require_once('view/head.php');
    require_once('view/pealeht.html');
    require_once('view/foot.html');
};

function on_logitud() {
    if (!empty($_SESSION['logitud'])) {
        if ($_SESSION['logitud'] == 'true') {
            return true;
        }
    }
    return false;
}
?>