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


/**
 *
 */
function kuva_testid () {
    if (!empty($_SESSION["roll"])) {
        global $connection;

        $query = "SELECT * FROM markask_kysimustikud";
        $testid =  mysqli_fetch_assoc(mysqli_query($connection, $query));

        if (($_SESSION["roll"])=="user") {
            //otsime praeguse kasutaja user_id üles
            $query = "SELECT id FROM markask_kasutajad WHERE user='{$_SESSION["user"]}'";
            $user_id =  mysqli_fetch_assoc(mysqli_query($connection, $query));

            lae_tulemused();
            foreach($testid as $test) {
                $query = "SELECT * FROM markask_tulemused WHERE kasutajad_id='{$user_id}'";
                $active_user = mysqli_fetch_assoc(mysqli_query($connection, $query));
                if ($test['kasutajad_id'] == $active_user) {
                    /////////siia kuvamis meetod!!!
                    // for (each result given) {
                    //prindi tabel}
                }
            }



        } elseif (($_SESSION["roll"])=="admin") {
            lae_testid();
        }
        require_once('view/head.php');
        require_once('view/testid.php');
        require_once('view/foot.html');

    } else {
        header("Location: ?mode=logisisse");
    };
}

function lae_testid() {
    return;
}

function lae_tulemused() {
    return;
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
            }
            //Kui tunnus ja salasõna on olemas, siis kontrollime nende vastavust andmebaasile
            else {
                $username = mysqli_real_escape_string($connection, $_POST["user"]);
                $passw = sha1(mysqli_real_escape_string($connection, $_POST["password"]));

                //testimiseks, peita pärast
                //print_r($username);
                //print_r($passw);

                // admin sha 6c7ca345f63f835cb353ff15bd6c5e052ec08e7a
                // kasutaja1 sha ea317b340f2fcf3f3adbed1d60fc7047261cfc59

                //otsime kasutajat andmebaasist, limiteerime tulemuse 1 vasteni, sest kasutajad on unikaalsed
                $query = "SELECT * FROM markask_kasutajad WHERE user='{$username}' AND password='{$passw}' LIMIT 1";
                $result = mysqli_query($connection, $query);

                //kui kasutaja leidub siis lisame sessiooni tema info
                if (mysqli_num_rows($result) > 0) {
                    $roll = mysqli_fetch_assoc(mysqli_query($connection, $query))['roll'];
                    print_r($roll);
                    alusta_sessioon();
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
    unset($_SESSION['pooleliuser']);
    global $connection;
    $errors = array();
    /*Kui kasutaja on juba sessioonis olemas, siis st. et on kasutja sisseloginud ja kasutanud linki
    või sirvija back funktsiooni - seega pole tal ka uut kasutaja tunnust vaja ja suuname ta otse testide leheküljele
    */
    if (!empty($_SESSION["user"])) {
        header("Location: ?mode=testid");

        //Kui kasutajat ei ole sessioonis alustame uue kasutaja registreerimis sessiooniga
    } else {

        //Kontrollime, kas kasutja üldse andmeid sisestas sisselogimise blanketil
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Kui jah siis kontrollime kas tunnus ja parool on olemas, kui midagi on puudu, siis suuname uuesti sisselogima
            if (empty($_POST["newuser"]) || empty($_POST["passwordx1"]) || empty($_POST["passwordx2"])) {

                //kui kasutajanimi on sisestamata lisame veateate
                if (empty($_POST["newuser"]))
                    $errors[] = "Kasutajanimi on sisestamata!";

                //Kui salasõna on sisestamata, siis lisame veateate, kui aga samas kasutja on olemas salvestame selle, et
                //hiljem sellega kasutaja elu veits lihtsamaks teha
                if (empty($_POST["passwordx1"]) || empty($_POST["passwordx2"]))
                    $errors[] = "Parool on sisestamata!";
                    if (!empty($_POST["newuser"])) {
                        $_SESSION['pooleliuser']=htmlspecialchars($_POST["newuser"]);}
            //kui paroolid ja kasutaja on olemas vaatame, et parooli klapiks, kui ei siis vaatame kas kasutja on olemas
                // ning salvestame selle, et hiljem sellega kasutaja elu veits lihtsamaks teha
            } else if ($_POST["passwordx1"] != $_POST["passwordx2"]) {
                $errors[] = "Paroolid ei klapi!";
                if (!empty($_POST["newuser"])) {
                    $_SESSION['pooleliuser']=htmlspecialchars($_POST["newuser"]);}
            } //Kui tunnus ja salasõna on olemas, siis kontrollime nende vastavust andmebaasile
            else {
                $newusername = mysqli_real_escape_string($connection, $_POST["newuser"]);
                $newpassw = sha1(mysqli_real_escape_string($connection, $_POST["passwordx1"]));

                //otsime kas sama nimega kasutaja on juba andmebaasis
                $query = "SELECT * FROM markask_kasutajad WHERE user='{$newusername}' LIMIT 1";
                $result = mysqli_query($connection, $query);

                //kui kasutajat ei ole siis lisame uue kasutaja
                if (mysqli_num_rows($result) <= 0) {

                    $sql = "INSERT INTO markask_kasutajad (user, password) VALUES ( '{$newusername}', '{$newpassw}')";

                    if ($connection->query($sql) === TRUE) {
                        $_SESSION['newuser']=$newusername;
                        $_SESSION['regamisteade'] = "Uus kasutaja lisatud! Palun logige oma tunnusega sisse.";
                        header("Location: ?mode=logisisse");
                    } else {
                        $errors[] = "Vabandame, kuid andmebaasiga ühendumises tekkis probleem, palun proovi uuesti!";
                    }
                    //vabastame päringu, kuna kasutaja päringut enam vaja ei lähe
                    mysqli_free_result($result);

                    //Kui tunnus leidub ja salasõna on õige logime kasutaja sisse

                } /* Kui kasutaja on juba olemas, siis lisame veateate ja laseme lõppu minna (uuessti registreerimis vormile) */
                else {
                    $errors[] = "Kasutajanimi on juba kasutusel!";
                }
            }

        }
        //kui kasutaja andmeid ei sisestanud, või läks midagi valesti siis anname talle välja kus ta saab seda (uuesti) teha
        require_once('view/head.php');
        require_once('view/registreeru.html');
        require_once('view/foot.html');
    }
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

?>