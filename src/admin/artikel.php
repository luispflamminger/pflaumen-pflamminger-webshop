<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger: Kategorien</title>
    <link rel="stylesheet" href="../style.css" />
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "admin");

    $con = db_verbinden();
    if(isset($_GET["katId"])) {
        $sql ="SELECT * FROM artikel WHERE kategorie=" . htmlspecialchars($_GET["katId"]);
    } else {
        $sql ="SELECT * FROM artikel";
    }
    $res = mysqli_query($con, $sql);
    mysqli_close($con);
    ?>
</head>
<body>
    <?php
    echo "<div>Hallo ". htmlspecialchars($_SESSION["vorname"]) . "!</div>";
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
            echo "<td>" . nl2br($dsatz["beschreibung"]) . "</td>";
            echo "<td>" . $dsatz["kategorie"] . "</td>";
            echo "<td>" . number_format($dsatz["preis"], 2, ',', '.') . "&nbsp;&euro;</td>";
            echo "<td><a href='art_bearbeiten.php?id=".$dsatz["id"]."&name=". $dsatz["name"] . "'>Bearbeiten</a> </td>";
            echo "</tr>";
        }
    ?>
    <tr>
        <td></td>
        <td colspan="4"><a href = "art_hinzufuegen.php?katId=<?php if (isset($_GET["katId"])) { echo htmlspecialchars($_GET["katId"]); }; ?>">Neuen Artikel hinzufügen</a></td>
        <td></td>
    </tr>
    </table>

</body>
</html>