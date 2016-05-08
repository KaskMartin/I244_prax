<?php
Foreach ($puurid as $puurinumber => $puur) {
    echo "
    <hr>
        <h1>Puur number $puurinumber<h1>
    <hr>
    ";
    echo "<div id='{$puurinumber}'>";
    foreach ($puur as $loom) {
        echo "<img src=\"{$loom['liik']}\" alt=\"{$loom['nimi']}\">";
    }
    echo "</div>";
};