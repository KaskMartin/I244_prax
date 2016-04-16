<?php require_once('head.html');
require_once('pildid.php');?>
<div id="wrap">
	<h3>Fotod</h3>
	<div id="gallery">
		<?php
		foreach ($pildid as &$value) {
			echo "<img src='$value[src]' alt='$value[alt]' /> \n";
		}
		?>
	</div>
</div>
<?php require_once('foot.html') ?>
