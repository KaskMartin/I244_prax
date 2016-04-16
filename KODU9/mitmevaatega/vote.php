<?php require_once('head.html');
require_once('pildid.php');?>
	<div id="wrap">
	<h3>Vali oma lemmik :)</h3>
	<form action="tulemus.php" method="GET">
		<?php
		$count = 1;
		foreach ($pildid as &$value) {
			echo "<p>\n
            <label for=\"p".$count."\">\n
                <img src=\"".$value['src']."\" alt=\"".$value['alt']."\" height=\"100\" />\n
            </label>\n
            <input type=\"radio\" value=\"".$value['alt']."\" id=\"p".$count."\" name=\"nimi\"/>
        </p>";
				$count++;}
		?>
		<br/>
		<input type="submit" value="Valin!"/>
	</form>

</div>
<?php require_once('foot.html') ?>