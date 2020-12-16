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

    <title>Pflaumen Pflamminger: Bewertung</title>

    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    if (isset($_GET["id"])) {
        $bewID = htmlspecialchars($_GET["id"]);
    } else {
        header("Location: start.php");
        exit;
    }

    $sql = "SELECT * FROM bewertung WHERE id=$bewID";

    $con = db_verbinden();
    $res = mysqli_query($con, $sql);
    $dsatzBew = mysqli_fetch_assoc($res);

    if (is_null($dsatzBew["bezug"])) {
        $istAntwort = false;
        $artikelID = $dsatzBew["artikel"];
    } else {
        $istAntwort = true;
        $hauptbeitragID = $dsatzBew["bezug"];
        $sql = "SELECT titel FROM bewertung WHERE id=$hauptbeitragID";
        $hauptbeitragTitel = mysqli_fetch_assoc(mysqli_query($con, $sql));    
        $hauptbeitragTitel = $hauptbeitragTitel["titel"];
    }

    $sql = "SELECT vorname, name FROM benutzer WHERE id=" . $dsatzBew["benutzer"];
    $dsatzName = mysqli_fetch_assoc(mysqli_query($con, $sql));

    mysqli_close($con);
    
    echo "<title>Pflaumen Pflamminger: " . $dsatzBew["titel"] . "</title>";
    ?>
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>

<body class="bg-light">
    <div class="container py-5" style="max-width: 60em;">
        <h2 class="mb-4">Kundenbewertung</h2>
        <div class="card mb-3">
        <?php
        echo "<div class='card-header'>Bewertung von " . $dsatzName["vorname"] . " " . $dsatzName["name"] . "<br>";
        echo $dsatzBew["bewertung"] . " von 5 Pflaumen<br>";
        if ($istAntwort == true) {
            echo "Antwort auf: <a href='bewertung.php?id=$hauptbeitragID'>" . $hauptbeitragTitel . "</a>";
        }
        echo "</div>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . $dsatzBew["titel"] . "</h5>";
        echo "<p class='card-text'>" . nl2br($dsatzBew["beschreibung"]) . "</p>";
        echo "<a class='card-link' href='antw_verfassen.php?id=" . $dsatzBew["id"] . "'>Antwort verfassen</a>";
        echo "</div></div>";
        ?>
        <hr class="mb-4">
        <h3 class="mb-4">Antworten</h3>

    <?php

        function antworten_anzeigen($id, $ebene) {
            $con = db_verbinden();
            $sql = "SELECT * FROM bewertung WHERE bezug=$id";
            $res = mysqli_query($con, $sql);
            while ($dsatzAntwort = mysqli_fetch_assoc($res)) {
                $sql = "SELECT vorname, name FROM benutzer WHERE id=" . $dsatzAntwort["benutzer"];
                $dsatzName = mysqli_fetch_assoc(mysqli_query($con, $sql));
                echo "<div class='card mb-3' style='margin-left: ${ebene}em'>";
                echo "<div class='card-header'>";
                echo "Antwort von " . $dsatzName["vorname"] . " " . $dsatzName["name"] . "<br>";
                echo $dsatzAntwort["bewertung"] . " von 5 Pflaumen</div>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . $dsatzAntwort["titel"] . "</h5>";
                echo "<p class='card-text'>" . nl2br($dsatzAntwort["beschreibung"]) . "</p>";
                echo "<a class='card-link' href='bewertung.php?id=" . $dsatzAntwort["id"] . "'>Antwort anzeigen</a>";
                echo "<a class='card-link' href='antw_verfassen.php?id=" . $dsatzAntwort["id"] . "'>Antwort verfassen</a>";
                echo "</div></div>";

                antworten_anzeigen($dsatzAntwort["id"], $ebene+3);
            }
            $ebeneTemp = $ebene - 3;
            if ($ebeneTemp != 0) {
            }
            mysqli_close($con);

        }
        antworten_anzeigen($dsatzBew["id"], 0);

        if ($istAntwort) {
            echo "<p><a href='bewertung.php?id=$hauptbeitragID'>Zurück</a></p>";
        } else {
            echo "<p><a href='artikel.php?id=$artikelID'>Zurück</a></p>";
        }
    ?>
    
</body>
</html>