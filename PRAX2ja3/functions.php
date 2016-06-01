<?php

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

/*
 * Funktsioon tabeli printimiseks array(array()) tyypi muutujast
 * Modified from http://stackoverflow.com/questions/15251095/display-data-from-sql-database-into-php-html-table
 * by Axel Arnold Bangert - Herzogenrath 2016
 */
function display_data($data) {
    $output = "<table class='db-table'>";
    foreach($data as $key => $var) {
        //$output .= '<tr>';
        if($key===0) {
            $output .= '<tr class="dp-heading">';
            foreach($var as $col => $val) {
                $output .= "<td>" . $col . '</td>';
            }
            $output .= '</tr>';
            foreach($var as $col => $val) {
                $output .= '<td>' . $val . '</td>';
            }
            $output .= '</tr>';
        }
        else {
            $output .= '<tr>';
            foreach($var as $col => $val) {
                $output .= '<td>' . $val . '</td>';
            }
            $output .= '</tr>';
        }
    }
    $output .= '</table>';
    echo $output;
}

/*
 * võtab sisenditena $connectioni ja SQL päringu ning tagastab Array(array()) formaadis tulemused
 */
function get_data ($sql, $con) {
    $result = mysqli_query($con, $sql);
    $tulemused=array();

    while($row = mysqli_fetch_assoc($result)) {
        $tulemused[] = $row;
    };
    return $tulemused;
}

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
                    $x=mysqli_fetch_assoc($result);
                    $roll = $x['roll'];
                    $user_id = $x['id'];
                    alusta_sessioon();
                    $_SESSION["user"] = $username;
                    $_SESSION["roll"] = $roll;
                    $_SESSION["user_id"] = $user_id;

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

                } /* Kui kasutaja on juba olemas, siis lisame veateate ja laseme lõppu minna (uuesti registreerimis vormile) */
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

/**
 *
 */
function kuva_testid () {
    if (empty($_SESSION["roll"]) || empty($_SESSION["user"])) {
        header("Location: ?mode=logisisse");
    } else {
        require_once('view/head.php');
        global $connection;

        $sql = "SELECT * FROM markask_kysimustikud";
        $kysimustikud = get_data($sql, $connection);

        //kui tegu on tavakasutajaga, hakkame tema tulemusi kuvama
        if (($_SESSION["roll"])=="user") {
            //otsime praeguse kasutaja user_id üles
            $sql = "SELECT * FROM markask_kasutajad WHERE user='{$_SESSION["user"]}' LIMIT 1";
            $user_id =  mysqli_fetch_assoc(mysqli_query($connection, $sql))['id'];

            //käime iga küsimustiku läbi
            foreach($kysimustikud as $kysimustik) {
                //prindime välja küsimustiku pealkirja
                echo "<h3>{$kysimustik["pealkiri"]}</h3>";

                //Otsime välja kasutaja tulemused, mis selle küsimustiku kohta on olemas
                $sql = "SELECT id as '#', millal_esitatud as 'Esitamise aeg', kaua_l2ks as 'Kulunud aeg', punkte as Tulemus, l2bitud as 'Läbitud' FROM markask_tulemused WHERE kasutajad_id='{$user_id}' AND kysimustikud_id='{$kysimustik['id']}'";
                $tulemused = get_data($sql, $connection);
                //prindime tulemused välja

                echo "<div id='tulemused'>";
                if (!empty($tulemused)){
                    display_data($tulemused);
                } else {
                    echo "Sa ei ole veel seda testi teinud!";
                }
                echo "</div><br>";

                //anname nupu testile vastamiseks
                echo "<button type='button' onclick=\"location.href='?mode=kysimused&qid={$kysimustik['id']}'\">Vasta testile</button>";
            }



        } elseif (($_SESSION["roll"])=="admin") {

            $kysitluse_number = 1;
            //käime iga küsimustiku läbi
            foreach($kysimustikud as $kysimustik) {
                //prindime välja küsimustiku pealkirja
                echo "<h3>Küsitlus nr. ".$kysitluse_number.".: ".$kysimustik["pealkiri"]."</h3>";
                $kysitluse_number++;
                //Otsime välja küsimused, mis selle küsimustiku kohta on olemas
                $sql = "SELECT id as '#', kysimus as 'Küsimus', max_punktid as 'Maksimum tulemus' FROM markask_kysimused WHERE kysimustik_id='{$kysimustik['id']}'";
                $tulemused = get_data($sql, $connection);
                //prindime tulemused välja
                echo "<div id='tulemused'>";
                display_data($tulemused);
                echo "</div>";
            }

            //siia tuleb tulevikus võimalus uusi küsimusi sisestada
            echo "<br><br><p style='font-size: small'>*Siia tuleb tulevikus võimalus lisada uusi küsitlusi ning küsimusi</p>";
        }

        require_once('view/testid.php');
        require_once('view/foot.html');

    }
}

/*$kysimused=array(
array("kysimus_id"=>"1", "kysimus"=>"mitu Sõrme on inimesel",
"vastused" => array (
    array("variant"=>"5", "value" => "false"),
    array("variant"=>"12", "value" => "false"),
    array("variant"=>"10", "value" => "true")
),
),*/

function kuva_kysimused () {

    if (empty($_SESSION["roll"]) || empty($_SESSION["user"])) {
        header("Location: ?mode=logisisse");
    } elseif (empty($_GET['qid'])) {
        header("Location: ?mode=VajutaIkka6igetNuppu");
    }   else {
        require_once('view/head.php');
        echo "<form method='POST' action='?mode=vastused'>";

        $qid = htmlspecialchars($_GET['qid']);
        echo "<input type='hidden' name='kysimustiku_id' value='{$qid}'>";
        $j2rjekorranumber = 1;
        global $connection;
        $sql = "SELECT id, kysimus FROM markask_kysimused WHERE kysimustik_id='{$qid}'";
        $kysimused = get_data($sql, $connection);

        //echo"kysimused<br>";
        //print_r2($kysimused);

        foreach ($kysimused as $kysimus) {
            echo "<h2>Küsimus nr.{$j2rjekorranumber}</h2>";
            echo "<p>{$kysimus['kysimus']}</p>";
            $j2rjekorranumber++;

            $sql = "SELECT id, vastuse_variant FROM markask_vastused WHERE kysimuse_id='{$kysimus['id']}'";
            $vastused = get_data($sql, $connection);

            //echo"vastused<br>";
            //print_r2($vastused);

            foreach ($vastused as $vastus){

                //echo "vastus <br> ";
                //print_r2($vastus);
                echo "<p><input type='radio' value='{$vastus['vastuse_variant']}' name='{$kysimus['id']}' id='{$kysimus['id']}{$vastus['vastuse_variant']}'><label for='{$kysimus['id']}{$vastus['vastuse_variant']}'>{$vastus['vastuse_variant']}</label></p>";
            }

        }
        echo "<p><input type=\"hidden\" name=\"timestamp\" id=\"timestamp\" value=\"".time()."\"/></p>";

        echo "<button type=\"submit\">Vasta!</button></form>";
        require_once('view/foot.html');
    }
};

function print_r2($val){
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
}

function kuva_vastused (){
    global $connection;
    $l6ppaeg=time();

    if (empty($_SESSION["roll"]) || empty($_SESSION["user"])) {
        header("Location: ?mode=logisisse");
    } else {

        require_once('view/head.php');
        //print_r2($_POST);
        $punkte = $vastatud = $oigesti_vastatud = $läbitud = 0;
        $qid = '';

        foreach ($_POST as $key => $val) {
            if (htmlspecialchars($key) == 'timestamp') {
                $algaeg = $val;
            } elseif (htmlspecialchars($key) == 'kysimustiku_id') {
                $qid = $val;
            } elseif (empty($qid)) {
                break;
            } else {
                $sql = "SELECT * FROM markask_vastused WHERE kysimuse_id = $key";
                $variandid = get_data($sql, $connection);
                foreach ($variandid as $variant) {
                    if ($variant['vastuse_variant'] == $val) {
                        $vastatud++;
                        $oigesti_vastatud += $variant['t6ev22rtus'];
                        $punkte += $variant['punkte'];
                        break;
                    }
                }
            }
        }

        $kulunud_aeg = date("H:i:s", $l6ppaeg - $algaeg - date('02:00:00'));
        $kasutajad_id = $_SESSION["user_id"];
        $sql = "SELECT * FROM `markask_kysimustikud` WHERE id=$qid";
        $k = get_data($sql, $connection)['0'];

        //print_r2($k);
        //print_r2($_SESSION);

        if ($punkte >= $k['l2vend'] && $kulunud_aeg < $k['ajalimiit']) {
            $läbitud = 1;
        }

        $sql = "INSERT INTO `markask_tulemused` (`kasutajad_id`, `kysimustikud_id`, `kaua_l2ks`, `punkte`, `l2bitud`) VALUES ( '{$kasutajad_id}' , '{$qid}','{$kulunud_aeg}' ,'{$punkte}' ,'{$läbitud}');";
        $result = mysqli_query($connection, $sql);
        if (!empty($result)) {
            echo "Andmed sisestatud!<br>";
            echo "
            kasutajad_id=$kasutajad_id<br>
            kysimustikud_i=$qid<br>
            kaua_l2ks=$kulunud_aeg<br>
            punkte=$punkte<br>
            l2bitud=$läbitud<br>";

            echo "<p>Tänan et leidsite aega vastata!</p>";
            require_once('view/foot.html');
        };
    }
}

function kuva_pealeht () {
    require_once('view/head.php');
    require_once('view/pealeht.html');
    require_once('view/foot.html');
};

?>