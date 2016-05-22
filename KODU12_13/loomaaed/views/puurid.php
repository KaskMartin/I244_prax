<?php
echo "<hr> user:";
print_r($_SESSION["user"]);
echo "<hr> roll: ";
print_r($_SESSION["roll"]);
echo "<hr>";
Foreach ($puurid as $puurinumber => $puur) {
    echo "
    <hr>
        <h1>Puur number $puurinumber<h1>
    <hr>
    ";
    echo "<div id='{$puurinumber}'>";
    foreach ($puur as $loom) {
        if ($_SESSION["roll"] == 'admin') {
        echo "<a href='?page=muuda&id={$loom['id']}'>";
        }

        echo "<img src=\"{$loom['liik']}\" alt=\"{$loom['nimi']}\">";

        if ($_SESSION["roll"] == 'admin') {
            echo "</a>";
        }
    }
    echo "</div>";
};