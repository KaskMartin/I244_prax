<?php
$_SESSION['logitud']="";

function connect_db()
{
    global $connection;
    $host = "localhost";
    $user = "test";
    $pass = "t3st3r123";
    $db = "test";
    $connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust mootoriga- " . mysqli_error());
    mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - " . mysqli_error($connection));
}

/**
 * Sessiooni käivitamis funktsioon. Kui sessioon on juba käimas, siis tagastab tühiväärtuse.
 */
function alusta_sessioon(){
    if (!isset($_SESSION)){
        ini_set("session.cookie_lifetime",30*60);
        session_start();
    } else return;
}


function lopeta_sessioon(){
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
}
function kuva_testid () {
    /* kuva logimise teade, kui seda on*/
    require_once('view/head.php');
    require_once('view/testid.html');
    require_once('view/foot.html');
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
            echo "<p><input type='radio' value='{$vastus['variant']}' name='{$kysimus['kysimus_id']}' id='{$kysimus['kysimus_id']}{$vastus['variant']}'><label for='{$kysimus['kysimus_id']}{$vastus['variant']}'>{$vastus['variant']}</label></p>";
        }

        echo "";
        $j2rjekorranumber++;
    };
    echo "<button type=\"submit\">Sisesta!</button></form>";

    require_once('view/foot.html');
};

function kuva_laekysimus () {
    /*Siia meetod kysimuste üles laadimiseks*/

    /*if (empty($_SESSION['logitud']) || $_SESSION['logitud'] != 'true' ) {
        $_SESSION['logimisteade']="Vabandame, kuid pilte lisada saavad ainult registreerunud kasutajad.";
        header('Location: ?mode=galerii');
        exit(0);
    }*/

    require_once('view/head.php');
    require_once('view/laekysimus.html');
    require_once('view/foot.html');
};

/**
 *
 */
function kuva_logisisse () {

    global $connection;
    $errors = array();
    /*Kui kasutaja on juba sessioonis olemas, siis st. et on kasutja sisseloginud ja kasutanud linki
    või sirvija back funktsiooni - uuesti sisse logida ei ole vaja suuname otse testide leheküljele
    */
    if (!empty($_SESSION["user"])) {
        header("Location: ?mode=testid");

        //Kui kasutajat ei ole sessioonis alustame sisselogimis sessiooniga
    } else {

        //Kontrollime, kas kasutja üldse andmeid sisestas sisselogimise blanketil
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Kui jah siis kontrollime kas parool ja tunnus on olemas, kui midagi on puudu, siis suuname uuesti sisselogima
            if (empty($_POST["user"]) || empty($_POST["password"])) {
                if (empty($_POST["user"]))
                    $errors[] = "Kasutajanimi on sisestamata!";

                if (empty($_POST["password"]))
                    $errors[] = "Parool on sisestamata!";

                header("Location: ?mode=logisisse");
            }
            //Kui tunnus ja salasõna on olemas, siis kontrollime nende vastavust andmebaasile
            else {
                $username = mysqli_real_escape_string($connection, $_POST["user"]);
                $passw = mysqli_real_escape_string($connection, $_POST["password"]);

                //otsime kasutajat andmebaasist, limiteerime tulemuse 1 vasteni, sest kasutajad on unikaalsed
                $query = "SELECT * FROM markask_kasutajad WHERE user='{$username}' AND password=sha1('{$passw}') LIMIT 1";
                $result = mysqli_query($connection, $query);

                if (mysqli_num_rows($result)) {
                    $roll = mysqli_fetch_assoc(mysqli_query($connection, $query))['roll'];
                    $_SESSION["user"] = $username;
                    $_SESSION["roll"] = $roll;

                    //vabastame päringu, kuna kasutaja päringut enam vaja ei lähe
                    mysqli_free_result($result);

                    //Kui tunnus leidub ja salasõna on õige logime kasutaja sisse
                    $_SESSION['logimisteade']="Olete edukalt sisse loginud!";
                    header("Location: ?mode=testid");
                }

                /* Kui vastavat tunnust ei ole andmebaasis, või salasõna on valesti, siis anname veateate
                 * et mitte pahalastele liialt infot anda, ei täpsusta kumb on valesti */
                else {
                    $errors[] = "Kasutajanimi või salasõna on vale!";
                    header("Location: ?mode=logisisse");
                }
            }

        }
        //kui kasutaja andmeid ei sisestanud anname talle välja kus ta saab seda teha
        require_once('view/head.php');
        include_once('view/logisisse.html');
        require_once('view/foot.html');
    }

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