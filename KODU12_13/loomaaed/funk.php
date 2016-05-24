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


function logi()
{
    global $connection;
    $errors = array();

    if (!empty($_SESSION["user"])) {
        header("Location: ?page=loomad");
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST["user"] == '' || $_POST["pass"] == '') {
                if (empty($_POST["user"]))
                    $errors[] = "Kasutajanimi on sisestamata!";

                if (empty($_POST["pass"]))
                    $errors[] = "Parool on sisestamata!";
            } else {
                $username = mysqli_real_escape_string($connection, $_POST["user"]);
                $passw = mysqli_real_escape_string($connection, $_POST["pass"]);
                $query = "SELECT 'id' FROM markask_kylastajad WHERE username='{$username}' AND passw=sha1('{$passw}') LIMIT 1";
                $result = mysqli_query($connection, $query);

                if (mysqli_num_rows($result)) {
                    mysqli_free_result($result);
                    $query = "SELECT * FROM markask_kylastajad WHERE username='{$username}' AND passw=sha1('{$passw}') LIMIT 1";
                    $roll = mysqli_fetch_assoc(mysqli_query($connection, $query))['roll'];
                    $_SESSION["user"] = $_POST["user"];
                    $_SESSION["roll"] = $roll;
                    header("Location: ?page=loomad");
                } else {
                    header("Location: ?page=login");
                }
            }
        }
    }
    include_once('views/login.html');
}

function logout()
{
    $_SESSION = array();
    session_destroy();
    header("Location: ?");
}

function kuva_puurid()
{
    // siia on vaja funktsionaalsust

    global $connection;
    if (empty($_SESSION["user"])) {
        header("Location: ?page=login");
    }

    connect_db();
    global $connection;
    $puurid = array();
    $puurinumbrid = array();

    $query_puurinumbrid = "SELECT DISTINCT(puur) FROM `markask_loomaaed`";
    $result_puurinumbrid = mysqli_query($connection, $query_puurinumbrid) or die("$query_puurinumbrid - " . mysqli_error($connection));
    while ($row = mysqli_fetch_array($result_puurinumbrid)) {
        $puurinumbrid[] = $row['puur'];
    }
    //Array ( [0] => 8 [1] => 2 [2] => 4 [3] => 5 [4] => 7 )
    foreach ($puurinumbrid as &$puurinumber) {
        $loomarida = array();
        $query_puurinumber = "SELECT * FROM `markask_loomaaed` WHERE puur=$puurinumber ORDER BY puur ASC";
        $result_loomad = mysqli_query($connection, $query_puurinumber) or die("$query_puurinumbrid - " . mysqli_error($connection));
        while ($loom = mysqli_fetch_assoc($result_loomad)) {
            $loomarida[] = $loom;
        }
        $puurid[$puurinumber] = $loomarida;
    }

    /* echo "<pre>";
     * print_r($puurid);
     * echo "</pre>";
     */

    include_once('views/puurid.php');
}

function lisa()
{
    global $connection;
    $errors = array();

    if (empty($_SESSION["user"]) && $_SESSION["roll"] == 'admin') {
        header("Location: ?page=login");

    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST["nimi"] == '' || $_POST["puur"] == '') {
                if (empty($_POST["nimi"])) {
                    $errors[] = "Nimi on sisestamata!";
                }
                if (empty($_POST["puur"])) {
                    $errors[] = "Puur on sisestamata!";
                }
            } else {
                upload('liik');
                $nimi = mysqli_real_escape_string($connection, $_POST["nimi"]);
                $puur = mysqli_real_escape_string($connection, $_POST["puur"]);
                $liik = mysqli_real_escape_string($connection, "pildid/" . $_FILES["liik"]["name"]);
                $sql = "INSERT INTO markask_loomaaed (nimi, puur, liik) VALUES ('{$nimi}','{$puur}','{$liik}')";
                $result = mysqli_query($connection, $sql);
                $id = mysqli_insert_id($connection);
                if ($id) {
                    header("Location: ?page=loomad");
                } else {
                    header("Location: ?page=loomad");
                }
            }
        }
    }
    include_once('views/loomavorm.html');
}

function upload($name)
{
    $allowedExts = array("jpg", "jpeg", "gif", "png");
    $allowedTypes = array("image/gif", "image/jpeg", "image/png", "image/pjpeg");
    $extension = end(explode(".", $_FILES[$name]["name"]));

    if (in_array($_FILES[$name]["type"], $allowedTypes)
        && ($_FILES[$name]["size"] < 100000)
        && in_array($extension, $allowedExts)
    ) {
        // fail õiget tüüpi ja suurusega
        if ($_FILES[$name]["error"] > 0) {
            $_SESSION['notices'][] = "Return Code: " . $_FILES[$name]["error"];
            return "";
        } else {
            // vigu ei ole
            if (file_exists("pildid/" . $_FILES[$name]["name"])) {
                // fail olemas ära uuesti lae, tagasta failinimi
                $_SESSION['notices'][] = $_FILES[$name]["name"] . " juba eksisteerib. ";
                return "pildid/" . $_FILES[$name]["name"];
            } else {
                // kõik ok, aseta pilt
                move_uploaded_file($_FILES[$name]["tmp_name"], "pildid/" . $_FILES[$name]["name"]);
                return "pildid/" . $_FILES[$name]["name"];
            }
        }
    } else {
        return "";
    }
}

function hangi($id)
{
    global $connection;
    $sql = "SELECT * FROM markask_loomaaed WHERE id={$id}";
    $result = mysqli_query($connection, $sql);
    $loom = mysqli_fetch_assoc($result);
    if (!empty($loom)) {
        return $loom;
    }
    return array();
}

function muuda()
{
    global $connection;
    $errors = array();
    $muudetavloom = array();

    if (empty($_SESSION["user"]) || $_SESSION["roll"] != 'admin') {
        header("Location: ?page=login");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST["id"])) {
            header("Location: ?page=loomad");
        } else {
            $temp_loom = hangi((int)$_POST["id"]);
            if (empty($temp_loom)) {
                $errors[] = "Muudetavat looma ei leitud andmebaasist!";
                header("Location: ?page=loomad");
            } else {
                $muudetavloom = $temp_loom;
            }
        };

        if (!empty($_POST["nimi"])) {
            $muudetavloom["nimi"] = mysqli_real_escape_string($connection, $_POST["nimi"]);
        };

        if (!empty($_POST["puur"])) {
            $muudetavloom["puur"] = mysqli_real_escape_string($connection, $_POST["puur"]);
        };

        if (!empty($_POST["liik"])) {
            upload('liik');
            $pilt = $_FILES["liik"]["name"];
            if (empty($pilt)) {
                $errors[] = "Looma pildi laadimine serverisse ebaõnnestus";
            } else {
                $muudetavloom["liik"] = mysqli_real_escape_string($connection, "pildid/" . $pilt);
            }
        };

        $sql = "UPDATE markask_loomaaed SET 'nimi'='{$muudetavloom["nimi"]}', 'puur'='{$muudetavloom["puur"]}', 'liik'='{$muudetavloom["liik"]}' WHERE id={$_POST['id']}";
        print_r($sql);
        /*
        mysqli_query($connection, $sql);
        header("Location: ?page=loomad");
        */

    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!empty($_GET['id'])) {
            $loom = hangi((int)$_GET['id']);
        }
        print_r($_GET['id']);
        print_r(hangi((int)$_GET['id']));

        include_once('views/head.html');
        include_once('views/editvorm.html');
    } else {
        header("Location: ?page=loomad");
    }


}

?>