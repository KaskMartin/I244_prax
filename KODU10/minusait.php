<?php

// vaikimisi väärtused:
$bg_color='midnightblue'; // vaikimisi sinine
$br_color='darkgreen'; // vaikimisi roheline
$br_width='8';
$br_rad='10';
$br_styl='double';
$tekst = 'See siin on näidis tekst!';
$tx_color = 'lightblue';

//muuda, kui sisend on antud:
if (!empty($_POST)) {
    if (isset($_POST['bg_color']) && $_POST['bg_color'] != "") {
        $bg_color = htmlspecialchars($_POST['bg_color']);
    }
    if (isset($_POST['tx_color']) && $_POST['tx_color'] != "") {
        $tx_color = htmlspecialchars($_POST['tx_color']);
    }
    if (isset($_POST['br_rad']) && $_POST['br_rad'] != "") {
        $br_rad = htmlspecialchars($_POST['br_rad']);
    }
    if (isset($_POST['br_width']) && $_POST['br_width'] != "") {
        $br_width = htmlspecialchars($_POST['br_width']);
    }
    if (isset($_POST['br_color']) && $_POST['br_color'] != "") {
        $br_color= htmlspecialchars($_POST['br_color']);
    }
    if (isset($_POST['br_styl']) && $_POST['br_styl'] != "") {
        $br_styl= htmlspecialchars($_POST['br_styl']);
    }

    if (isset($_POST['tekst']) && $_POST['tekst'] != "") {
        $tekst = htmlspecialchars($_POST['tekst']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input</title>
    <meta charset="utf-8" />
    <style>

        .kastike {
            background-color: <?php echo "$bg_color"; ?>;
            color: <?php echo "$tx_color"; ?>;
            border-radius: <?php echo "$br_rad"; ?>px;
            border-color: <?php echo "$br_color"; ?>;
            border-width: <?php echo "$br_width"; ?>px;
            border-style: <?php echo "$br_styl"; ?>;
            min-height: 100px;
            padding: 5px;

        }

        body {
            max-width: 600px;
            margin: 5px;
            font-family: "DejaVu Sans", "calibri", Garamond, 'Comic Sans', sans-serif;
        }
    </style>
</head>


<body>
<p class="kastike">
    <?php echo "$tekst"; ?>
</p>
<hr style="border-width: 2px">
<p>
<form action="minusait.php" method="post">
    <textarea name="tekst" placeholder='<?php echo "$tekst";?>'><?php echo "$tekst";?></textarea><br>
    <input type="color" name="bg_color" value='<?php echo "$bg_color"; ?>'> Taustavärvus<br>
    <input type="color" name="tx_color" value='<?php echo "$tx_color"; ?>'> Tekstivärvus<br>

    <div style="border-style: solid; border-color: silver; border-width: 2px; border-right-style: hidden; margin: 10px">
        <div style="position: relative; top: -11px; left: 10px; background-color: white; display: inline-block; margin: 2px">Piirjoon</div>
        <br>
        <input type="range" name="br_width" min="0" max="20" step="1" value='<?php echo "$br_width"; ?>'>Piirjoone laius (0-20px)<br>
        <select name="br_styl" value=' <?php echo "$br_styl"; ?>'>
                <option value='hidden' <?php if ($br_styl == 'hidden' ) echo 'selected' ; ?>>hidden</option>
                <option value='double' <?php if ($br_styl == 'double' ) echo 'selected' ; ?>>double</option>
                <option value='solid'  <?php if ($br_styl == 'solid' )  echo 'selected' ; ?>>solid</option>
                <option value='dotted' <?php if ($br_styl == 'dotted' ) echo 'selected' ; ?>>dotted</option>
                <option value='outset' <?php if ($br_styl == 'outset' ) echo 'selected' ; ?>>outset</option>
            </select>
        </select><br>
        <input type="color" name="br_color" value='<?php echo "$br_color"; ?>'>Piirjoone värvus<br>
        <input type="range" step="1" name="br_rad" min="0" max="100"  value='<?php echo "$br_rad"; ?>'> Piirjoone nurga raadius (0-100px)<br>
    </div>

    <input type="submit" value="Esita">

</form>
</p>
<p>
    <a href="http://validator.w3.org/check?uri=referer">
        <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" id="w3"/>
    </a>
</p>

</body>
</html>
