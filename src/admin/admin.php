<!DOCTYPE html>
<html>
<head>
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