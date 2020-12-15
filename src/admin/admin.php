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
    authentifizierung($_SESSION["typ"], "admin");

    if(isset($_GET["gelöscht"]) && $_GET["gelöscht"] == "true")
    {
        $meldung = "Datensatz gelöscht.";
    }
    else if (isset($_GET["gelöscht"]) && $_GET["gelöscht"] == "false") {
        $meldung = "Beim Löschen des Datensatzes ist etwas schief gelaufen.";
    }
    else {
        $meldung = "";
    }
    //$con = mysqli_connect("","root");
    //mysqli_select_db($con,"pflaumenshop");
    $con = db_verbinden();
    $sql ="SELECT * FROM kategorie";
    $res = mysqli_query($con, $sql);
    ?>
</head>


<?php 
/* Navbar
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "\\")+1);
require "../navbar.php"; 
*/
?>

<body>
    <?php
    echo "<div>Hallo ".$_SESSION["vorname"]."!</div>";
    echo $meldung;
        
    ?>
    <table border = "1">
        <tr>
            <th>Kategoriename</th><th></th>
        </tr>
    <?php
    while ($dsatz = mysqli_fetch_assoc($res)) { 
        echo "<tr>";
        echo "<td><a href='artikel.php?katId=" . $dsatz["id"] . "'>".$dsatz["name"]."</a></td>";
        echo "<td> <a href='kat_bearbeiten.php?id=".$dsatz["id"]."&name=".htmlspecialchars($dsatz["name"])."'>Bearbeiten</a> </td>";
        echo "</tr>";
    }
    ?>
        <tr>
            <td><a href = "kat_hinzufuegen.php">Neue Kategorie hinzufügen</a></td><td></td>
        </tr>
    </table>
</body>
</html>