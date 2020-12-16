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

    <title>Pflaumen Pflamminger: Admin Bereich</title>
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

<body class="bg-light">
    <div class="container text-center mt-5">
        <?php if ($meldung != "") { echo "<div class='alert alert-danger'>$meldung</div>"; } ?>
        <p class="lead text-muted">
            <?php
            echo "Hallo ". $_SESSION["vorname"] . "!";
            ?>
        </p>
        <h1>Kategorien</h1>
        <p class="lead text-muted">Wähle eine Kategorie aus, bearbeite sie oder füge eine neue hinzu.</p>
    </div>

    <div class="container py-5" style="max-width: 40em;">
        <table class="table table-striped">
            <tr>
                <th>Kategoriename</th><th></th>
            </tr>
        <?php
        while ($dsatz = mysqli_fetch_assoc($res)) { 
            echo "<tr>";
            echo "<td><a href='artikel.php?katId=" . $dsatz["id"] . "'>" . $dsatz["name"] . "</a></td>";
            echo "<td style='text-align: right;'> <a class='btn btn-secondary' href='kat_bearbeiten.php?id=" . $dsatz["id"] . "&name=" . $dsatz["name"] . "'>Bearbeiten</a> </td>";
            echo "</tr>";
        }
        mysqli_close($con);
        ?>
            <tr>
                <td><a class='btn btn-primary' href = "kat_hinzufuegen.php">Neue Kategorie hinzufügen</a></td><td></td>
            </tr>
        </table>
    </div>
</body>
</html>