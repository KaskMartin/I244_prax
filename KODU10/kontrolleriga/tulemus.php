<?php
$valitudPildiLink ='';
$valitudPildiNimi ='';
$valitudPildihtml ='';
$output = array();



if (!empty($_POST)) {
    foreach ($pildid as &$mingiPilt) {
        if ($mingiPilt['alt'] == $_POST["nimi"]) {
            $valitudPildiLink = htmlspecialchars($mingiPilt['src']);
            $valitudPildiNimi = htmlspecialchars($mingiPilt['alt']);
            $valitudPildihtml = "<p>Valisite pildi nimega <strong>".$valitudPildiNimi."</strong></p></br><div id=\"gallery\"><img src=\"".$valitudPildiLink."\" alt=\"".$valitudPildiNimi."\" /> </div>";
        }
    }

    if (empty($_SESSION["voted_for"])) {
        $_SESSION["voted_for"] = $valitudPildiNimi;
        $output[] = "Täname osalemast pildi valikus!";
        $output[] = $valitudPildihtml;
        $output[] = "<a href='?mode=logiv2lja'>Lõpeta sessioon!</a>";
    } else {
        $output[] = "Teie arvamusega on juba arvestatud!";
        $output[] = $valitudPildihtml;
        $output[] = "<a href='?mode=logiv2lja'>Lõpeta sessioon!</a>";
    }
}
else {
    if (empty($_SESSION["voted_for"])) {
        $output[] = "Teil ununes pilt valimata! Minge <a href='?mode=vote'>tagasi hääletama</a> ja valige oma lemmik pilt.";
    }
    else {
        foreach ($pildid as &$mingiPilt) {
            if ($mingiPilt['alt'] == $_SESSION['voted_for']) {
                $valitudPildiLink = htmlspecialchars($mingiPilt['src']);
                $valitudPildiNimi = htmlspecialchars($mingiPilt['alt']);
                $valitudPildihtml = "<p>Valisite pildi nimega <strong>".$valitudPildiNimi."</strong></p></br><div id=\"gallery\"><img src=\"".$valitudPildiLink."\" alt=\"".$valitudPildiNimi."\" /> </div>";
            }
        }
        $output[] = "Teie arvamusega on juba arvestatud!";
        $output[] = $valitudPildihtml;
        $output[] = "<a href='?mode=logiv2lja'>Lõpeta sessioon!</a>";
    }
}
?>

<div id="wrap">
	<h3>Valiku tulemus</h3>
        <?php
            if(!empty($output)) {
                foreach ($output as $out) {
                    echo $out . "</br>";}
            }
        ?>
</div>
