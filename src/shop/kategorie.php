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
    <link rel="stylesheet" href="../style.css" />
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
require "../navbar.php"; ?>

<body>
    <h2><?php echo $katName; ?></h2>
    <table border="1">
        <tr>
            <th><!--Bild--></th>
            <th>Artikelname</th>
            <th>Beschreibung</th>
            <th>Preis</th>
        </tr>
        <?php
        while ($dsatz = mysqli_fetch_assoc($res)) { 
            echo "";
            echo "<tr>";
            echo "<td><img src='../../" . $dsatz["bild"] . "' width='100' height='100' /></td>";
            echo "<td><a href='artikel.php?id=".$dsatz["id"]."&name=".$dsatz["name"]."'>" . $dsatz["name"] . "</a></td>";
            echo "<td>" . nl2br($dsatz["beschreibung"]) . "</td>";
            echo "<td>" . number_format($dsatz["preis"], 2, ',', '.') . "&nbsp;&euro;</td>";
            echo "</tr>";
        }

        mysqli_close($con);
        ?>

    </table>

    <p><a href="start.php">Zur Startseite</a></p>
</body>
</html>