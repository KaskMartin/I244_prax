<?php


function connect_db(){
	global $connection;
	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust mootoriga- ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
}

function logi(){
	// siia on vaja funktsionaalsust (13. nädalal)

	include_once('views/login.html');
}

function logout(){
	$_SESSION=array();
	session_destroy();
	header("Location: ?");
}

function kuva_puurid(){
	// siia on vaja funktsionaalsust
	connect_db();
	global $connection;
	$puurid = array();
	$puurinumbrid = array();

	$query_puurinumbrid ="SELECT DISTINCT(puur) FROM `markask_loomaaed`";
	$result_puurinumbrid = mysqli_query($connection, $query_puurinumbrid) or die("$query_puurinumbrid - ".mysqli_error($connection));
	while($row = mysqli_fetch_array($result_puurinumbrid)) {
		$puurinumbrid[] = $row['puur'];
	}
	//Array ( [0] => 8 [1] => 2 [2] => 4 [3] => 5 [4] => 7 )
	foreach ($puurinumbrid as &$puurinumber) {
		$loomarida = array();
		$query_puurinumber = "SELECT * FROM `markask_loomaaed` WHERE puur=$puurinumber";
		$result_loomad = mysqli_query($connection, $query_puurinumber) or die("$query_puurinumbrid - ".mysqli_error($connection));
		while($loom = mysqli_fetch_assoc($result_loomad)) {
			$loomarida[] = $loom;
		}
		$puurid[$puurinumber]=$loomarida;
	}

	/* echo "<pre>";
	 * print_r($puurid);
	 * echo "</pre>";
	 */

	include_once('views/puurid.php');
}

function lisa(){
	// siia on vaja funktsionaalsust (13. nädalal)

	include_once('views/loomavorm.html');
	
}

function upload($name){
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$allowedTypes = array("image/gif", "image/jpeg", "image/png","image/pjpeg");
	$extension = end(explode(".", $_FILES[$name]["name"]));

	if ( in_array($_FILES[$name]["type"], $allowedTypes)
		&& ($_FILES[$name]["size"] < 100000)
		&& in_array($extension, $allowedExts)) {
    // fail õiget tüüpi ja suurusega
		if ($_FILES[$name]["error"] > 0) {
			$_SESSION['notices'][]= "Return Code: " . $_FILES[$name]["error"];
			return "";
		} else {
      // vigu ei ole
			if (file_exists("pildid/" . $_FILES[$name]["name"])) {
        // fail olemas ära uuesti lae, tagasta failinimi
				$_SESSION['notices'][]= $_FILES[$name]["name"] . " juba eksisteerib. ";
				return "pildid/" .$_FILES[$name]["name"];
			} else {
        // kõik ok, aseta pilt
				move_uploaded_file($_FILES[$name]["tmp_name"], "pildid/" . $_FILES[$name]["name"]);
				return "pildid/" .$_FILES[$name]["name"];
			}
		}
	} else {
		return "";
	}
}

?>