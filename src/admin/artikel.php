<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger: Kategorien</title>
    <link rel="stylesheet" href="../style.css" />
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "admin");

    /* Meldungen für Löschen etc.        
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
    */

    $con = db_verbinden();
    if(isset($_GET["katId"])) {
        $sql ="SELECT * FROM artikel WHERE kategorie=" . $_GET["katId"];
    } else {
        $sql ="SELECT * FROM artikel";
    }
    $res = mysqli_query($con, $sql);
    ?>
</head>
<body>
    <?php
    echo "<div>Hallo ".$_SESSION["vorname"]."!</div>";
    //echo $meldung;
    
    ?>
    <table border = "1">
        <tr>
            <th><!--Bild--></th>
            <th>Artikelname</th>
            <th>Beschreibung</th>
            <th>Kategorie</th>
            <th>Preis</th>
        </tr>
    <?php
        while ($dsatz = mysqli_fetch_assoc($res)) { 
            echo "<tr>";
            echo "<td><img src='../../" . $dsatz["bild"] . "' width='100' height='100' /></td>";
            echo "<td>" . $dsatz["name"] . " </td>";
            echo "<td>" . htmlspecialchars($dsatz["beschreibung"]) . "</td>";
            echo "<td>" . htmlspecialchars($dsatz["kategorie"]) . "</td>";
            echo "<td>" . number_format($dsatz["preis"], 2, ',', '.') . "&nbsp;&euro;</td>";
            echo "<td><a href='art_bearbeiten.php?id=".$dsatz["id"]."&name=".htmlspecialchars($dsatz["name"])."'>Bearbeiten</a> </td>";
            echo "</tr>";
        }
    ?>
    <tr>
        <td></td>
        <td colspan="4"><a href = "art_hinzufuegen.php?katId=<?php if (isset($_GET["katId"])) { echo $_GET["katId"]; }; ?>">Neuen Artikel hinzufügen</a></td>
        <td></td>
    </tr>
    </table>

</body>
</html>