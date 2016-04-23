<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="view/style.css">
    <title>I244 Prax, Martin Kask</title>
    <script src="http://code.jquery.com/jquery-1.12.2.min.js"></script>
</head>

<body>
<div class="lehesisu">
    <h1 class="Pealkiri">Galerii</h1>
    <ul class="menu">
        <li>
            <a href="?mode=pealeht">Pealeht</a>
        </li>

        <li>
            <a href="?mode=galerii">Galerii</a>
        </li>

        <li>
            <a <?php if (on_logitud() ) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
            ?> href="?mode=logisisse">Logi Sisse</a>
        </li>


        <li>
            <a <?php if (on_logitud() ) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
            ?> href="?mode=registreeru">Registreeru</a>
        </li>

        <li>
            <a <?php if (!on_logitud() ) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
            ?> href="?mode=laefail">Lae Faile Üles</a>
        </li>

        <li>
            <a <?php if (!on_logitud() ) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
               ?> href="?mode=logiv2lja">Logi välja</a>
        </li>
    </ul>

    <div id="teade"><?php
    if (!empty($_SESSION['logimisteade'])) {
        echo "<div id='teade' style='font-weight: bolder'>{$_SESSION['logimisteade']}</div>>";
        unset($_SESSION['logimisteade']);
    }

    ?></div>