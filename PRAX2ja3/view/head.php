<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="view/style.css">
    <title>Questionaator</title>
    <script src="http://code.jquery.com/jquery-1.12.2.min.js"></script>
</head>

<body>
<div id="container">
    <div id="content">
    <h1 class="Pealkiri">Questionaator</h1>

    <ul class="menu">
        <li>
            <a href="?mode=pealeht">Pealeht</a>
        </li>

        <li>
            <a <?php if (!empty($_SESSION["user"])) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
            ?> href="?mode=logisisse">Logi Sisse</a>
        </li>


        <li>
            <a <?php if (!empty($_SESSION["user"])) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
            ?> href="?mode=registreeru">Registreeru</a>
        </li>

        <li>
            <a <?php if (empty($_SESSION["user"])) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
            ?> href="?mode=testid">Testid</a>
        </li>

        <li>
            <a <?php if (empty($_SESSION["user"])) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
            ?> href="?mode=kysimused">Küsimused</a>
        </li>

        <li>
            <a <?php if (empty($_SESSION["user"])) {
                echo "style = 'display: none'";}
            else {echo "style = 'display: inherit'";}
               ?> href="?mode=logiv2lja">Logi välja</a>
        </li>

        <?php if (!empty($_SESSION["user"])) {
        echo "<li id='kasutajainfo'>kasutaja: {$_SESSION['user']} <br>";
            echo "roll: {$_SESSION['roll']} </li>";
        }
        ?>
    </ul>