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

    //Artikel hinzufügen
    if(isset($_POST["hinzufuegen"])) {
        if (isset($_SESSION["warenkorb"][0])) {
            $_SESSION["warenkorb"][count($_SESSION["warenkorb"])] = array("id" => htmlspecialchars($_POST["artikelID"]), "anzahl" => htmlspecialchars($_POST["anzahl"]));
        } else {
            $_SESSION["warenkorb"][0] = array("id" => htmlspecialchars($_POST["artikelID"]), "anzahl" => htmlspecialchars($_POST["anzahl"]));
        }
    }

    $con = db_verbinden();
    ?>
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>

<body>
    <h2>Warenkorb</h2>
    <div>
        <table border = "1">
            <tr>
                <th align="left">Artikel</th>
                <th align="left">Nr.</th>
                <th align="left">Einzelpreis</th>
                <th align="left">Anzahl</th>
                <th align="left">Gesamtpreis</th>
            </tr>
        <?php
        $gesamtpreis = 0;
        foreach ($_SESSION["warenkorb"] as $pos) {
            $sql = "SELECT * FROM artikel WHERE id=" . $pos["id"];
            $dsatz = mysqli_fetch_assoc(mysqli_query($con, $sql));
            $positionspreis = $pos["anzahl"] * $dsatz["preis"];
            echo "<tr>";
            echo "<td>" . $dsatz["name"] . "</td>";
            echo "<td align='right'>" . $dsatz["id"] . "</td>";
            echo "<td align='right'>" . number_format($dsatz["preis"], 2, ',', '.') . " €</td>";
            echo "<td align='right'>" . $pos["anzahl"] . "</td>";
            echo "<td align='right'>" . number_format($positionspreis, 2, ',', '.') . " €</td>";
            echo "</tr>";
            $gesamtpreis += $positionspreis;
        }
        echo "<tr>";
        echo "<td colspan='4'>Gesamteinkaufspreis</td>";
        echo "<td align='right'>" . number_format($gesamtpreis, 2, ',', '.') . " €</td>";
        echo "</tr>";
        $_SESSION["gesamtpreis"] = $gesamtpreis;

        mysqli_close($con);
        ?>
        </table>
        <p><a href="kasse.php">Zur Kasse</a></p>
    </div>
</body>
</html>