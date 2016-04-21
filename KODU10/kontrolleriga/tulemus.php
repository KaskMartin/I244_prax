<?php $lingike ='';
?>
	<div id="wrap">
	<h3>Valiku tulemus</h3>
	<p>
		<?php if (!empty($_POST)) {

            foreach ($pildid as &$value) {
                if ($value['alt'] == $_POST["nimi"]){
                    $lingike = $value['src'];
                }
            }

			if ($_POST["nimi"] != "") {
				echo "Valisite pildi nimega ".htmlspecialchars($_POST["nimi"])."</br>";
				echo "</br>";
				echo "<div id=\"gallery\"><img src=\"".$lingike."\" alt=\"".$_POST["nimi"]."\" /> </div>";
			}
		}
		else {
			echo "Teil ununes pilt valimata! Minge tagasi galeriise ja valige oma lemmik pilt.";
		}
		?>

	</p>

</div>
