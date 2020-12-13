<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
                integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
                crossorigin="anonymous">
        <link rel="stylesheet" href="../custom-style.css">

    <title>Pflaumen Pflamminger</title>

    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    $con = db_verbinden();
    $sql ="SELECT * FROM kategorie";
    $res = mysqli_query($con, $sql);
    ?>
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>
<body>

    <?php

    echo "<h2>Willkommen bei Pflaumen Pflamminger, ".$_SESSION["vorname"]."!</h2>";     
    echo __FILE__; 
    ?>

    <div>
        <p>Wähle eine Kategorie:</p>
        <table border = "1">
            <tr>
                <th>Kategoriename</th>
            </tr>
        <?php
        while ($dsatz = mysqli_fetch_assoc($res)) { 
            echo "<tr>";
            echo "<td><a href='kategorie.php?katId=" . $dsatz["id"] . "&katName=" . $dsatz["name"] . "'>".$dsatz["name"]."</a></td>";
            echo "</tr>";
        }
        ?>
        </table>

    </div>
    <div>
        <br>
        <a href = "warenkorb.php">Zum Warenkorb</a>
    </div>
</body>
</html>