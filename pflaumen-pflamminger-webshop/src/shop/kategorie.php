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

    <title>Pflaumen Pflamminger: Kategorien</title>
    
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    if (isset($_GET["katName"])) {
        $katName = htmlspecialchars($_GET["katName"]);
    } else {
        $katName = "";
    }

    if (isset($_GET["katId"]) && $_GET["katId"] != "") {
        $sql ="SELECT * FROM artikel WHERE kategorie = " . htmlspecialchars($_GET["katId"]);
    } else {
        $sql ="SELECT * FROM artikel";
    }

    $con = db_verbinden();
    $res = mysqli_query($con, $sql);

    echo "<title>Pflaumen Pflamminger: $katName</title>";
    ?>

    
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>

<body class="bg-light">
    <div class="container text-center mt-5">
        <h1><?php if ($katName == "") { echo "Alle Artikel"; } else { echo $katName; } ?></h1>
    </div>
    <div class="container py-5" style="max-width: 80em;">
        <table class="table table-striped">
            <tr>
                <th><!--Bild--></th>
                <th>Artikelname</th>
                <th>Beschreibung</th>
                <th style='text-align: right;'>Preis</th>
            </tr>
            <?php
            while ($dsatz = mysqli_fetch_assoc($res)) { 
                echo "";
                echo "<tr>";
                echo "<td><img src='../../" . $dsatz["bild"] . "' width='100' height='100' /></td>";
                echo "<td><a style='display: block;' href='artikel.php?id=".$dsatz["id"]."&name=".$dsatz["name"]."'>" . $dsatz["name"] . "</a></td>";
                echo "<td>" . nl2br($dsatz["beschreibung"]) . "</td>";
                echo "<td style='text-align: right;'>" . number_format($dsatz["preis"], 2, ',', '.') . "&nbsp;&euro;</td>";
                echo "</tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</body>
</html>