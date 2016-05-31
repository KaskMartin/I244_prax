<?php
alusta_sessioon();

if (!empty($_SESSION['logimisteade'])) {
    echo "<div id='teade' style='font-weight: bolder'>{$_SESSION['logimisteade']}</div>";
    unset($_SESSION['logimisteade']);
}

?>