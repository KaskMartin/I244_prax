<!DOCTYPE html>
<html>
<?php
$kassid= array(
    array('nimi'=>'Miisu', 'vanus'=>2, 'varvus' => 'pruun', 'color' => 'brown','pilt' => '../PRAX2ja3ja4ja5ja7/thumb/cat_thumbnail.jpg'),
    array('nimi'=>'Tom', 'vanus'=>1, 'varvus' => 'sinine', 'color' => 'blue', 'pilt' => '../PRAX2ja3ja4ja5ja7/thumb/thmb_cat3_pic.png'),
    array('nimi'=>'Pätu', 'vanus'=>5, 'varvus' => 'punane', 'color' => 'red', 'pilt' => '../PRAX2ja3ja4ja5ja7/thumb/thmb_cat4_pic.png'),
    array('nimi'=>'Sämmy', 'vanus'=>12, 'varvus' => 'must', 'color' => 'black', 'pilt' => '../PRAX2ja3ja4ja5ja7/thumb/thmb_cat5_pic.png'),
    array('nimi'=>'Kenn', 'vanus'=>3, 'varvus' => 'lilla', 'color' => 'purple', 'pilt' => '../PRAX2ja3ja4ja5ja7/thumb/thmb_cat6_pic.png'),
    array('nimi'=>'Ruff', 'vanus'=>4, 'varvus' => 'kollane', 'color' => 'yellow', 'pilt' => '../PRAX2ja3ja4ja5ja7/thumb/thmb_cat7_pic.PNG')
);?>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <title>I244 KASSID!</title>
</head>

<style>
    <?php
    foreach ($kassid as $kass) {
    echo ".".
    $kass['varvus']."{color:".$kass['color'].";}";
};
        ?>
</style>

<?php
foreach ($kassid as $kass) {
    include 'kassid.html';
};
?>