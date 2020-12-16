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

    <title>Pflaumen Pflamminger: Warenkorb</title>

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

<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Warenkorb</h1>
        <?php
        if (isset($_SESSION["warenkorb"])) {
        ?>
    </div>
    <div class="container py-5" style="max-width: 45em;">
        <table class="table table-striped">
            <tr>
                <th >Artikel</th>
                <th style="text-align: right;">Nr.</th>
                <th style="text-align: right;">Einzelpreis</th>
                <th style="text-align: right;">Anzahl</th>
                <th style="text-align: right;">Gesamtpreis</th>
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
            ?>
            <tr>
                <td colspan="4">Gesamteinkaufspreis</td>
                <td align="right"><?php echo number_format($gesamtpreis, 2, ',', '.'); ?> €</td>
            </tr>
            <?php
            $_SESSION["gesamtpreis"] = $gesamtpreis;
            ?>
        </table>
        <p><a class='btn btn-primary' href='kasse.php'>Zur Kasse</a></p>
    </div>
            <?php
        } else {
            ?>
        <p class="lead text-muted">Noch keine Artikel im Warenkorb!</p>
    </div>
            <?php
        }
        mysqli_close($con);
        ?>

</body>
</html>