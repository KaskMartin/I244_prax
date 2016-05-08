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

function kuva_kysimused () {
    require_once('view/kysimused.php');
    require_once('view/head.php');

    //display kysimused from $kysimused
    echo "<form method='POST' action='?mode=vastused'>";
    global $kysimused;
    $j2rjekorranumber = 1;
    foreach ($kysimused as &$kysimus) {
        //print_r($kysimus);
        echo "<h2>Küsimus nr.{$j2rjekorranumber}</h2>";
        echo "<p>{$kysimus['kysimus']}</p>";
        foreach ($kysimus['vastused'] as &$vastus) {
            echo "<p><input type='radio' value='{$vastus['variant']}' name='{$kysimus['kysimus_id']}'>{$vastus['variant']}</input></p>";
        }

        echo "";
        $j2rjekorranumber++;
    };
    echo "<button type=\"submit\">Sisesta!</button></form>";

    require_once('view/foot.html');
};

function kuva_laekysimus () {
    if (empty($_SESSION['logitud']) || $_SESSION['logitud'] != 'true' ) {
        $_SESSION['logimisteade']="Vabandame, kuid pilte lisada saavad ainult registreerunud kasutajad.";
        header('Location: ?mode=galerii');
        exit(0);
    }
    require_once('view/head.php');
    require_once('view/laekysimus.html');
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
            header('Location: ?mode=kysimused');
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

function kuva_vastused (){
    include('view/kysimused.php');
    $id = 0;
    global $kysimused;
    $_SESSION['valedvastused'] = 0;
    $_SESSION['$oigedvastused'] = 0;

    if (empty($_POST)) {
        // $_SESSION['vastatud']='false';
        header('Location: ?mode=kysimused');
    } else {
        foreach ($_POST as $v_id => $v) {
            foreach ($kysimused as &$kysimus) {
                if ($kysimus['kysimus_id'] == $v_id) {
                    foreach ($kysimus['vastused'] as $kysimusevariant){
                        if ($kysimusevariant['variant'] == $v)
                            if ($kysimusevariant['value'] == 'true') {
                                $_SESSION['$oigedvastused']++;
                            } else {
                                $_SESSION['valedvastused']++;
                            }
                    }
                }
            }
        }
        $tulemusi_kokku = $_SESSION['$oigedvastused'] + $_SESSION['valedvastused'];
        require_once('view/head.php');

        echo "<div id='vastus'><p>Sinu tulemus: Said  {$_SESSION['$oigedvastused']} õiget vastust {$tulemusi_kokku} vastusest</p></div>";

        require_once('view/foot.html');
    };
}

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