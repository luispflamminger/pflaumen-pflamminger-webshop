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

    <title>Pflaumen Pflamminger: Artikel</title>

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
<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Artikel</h1>
        <p class="lead text-muted">Bearbeite einen Artikel oder füge einen neuen hinzu.</p>
    </div>

    <div class="container py-5" style="max-width: 80em;">
    <table class="table table-striped">
        <tr>
            <th><!--Bild--></th>
            <th>Artikelname</th>
            <th>Beschreibung</th>
            <th>Kategorie</th>
            <th>Preis</th>
            <th></th>
        </tr>
    <?php
        while ($dsatz = mysqli_fetch_assoc($res)) { 
            echo "<tr>";
            echo "<td><img src='../../" . $dsatz["bild"] . "' width='100' height='100' /></td>";
            echo "<td>" . $dsatz["name"] . " </td>";
            echo "<td>" . nl2br($dsatz["beschreibung"]) . "</td>";
            echo "<td>" . $dsatz["kategorie"] . "</td>";
            echo "<td>" . number_format($dsatz["preis"], 2, ',', '.') . "&nbsp;&euro;</td>";
            echo "<td style='text-align: right;'><a class='btn btn-secondary' href='art_bearbeiten.php?id=".$dsatz["id"]."&name=". $dsatz["name"] . "'>Bearbeiten</a> </td>";
            echo "</tr>";
        }
    ?>
    <tr>
        <td colspan="4"><a class='btn btn-primary' href = "art_hinzufuegen.php?katId=<?php if (isset($_GET["katId"])) { echo htmlspecialchars($_GET["katId"]); }; ?>">Neuen Artikel hinzufügen</a></td>
        <td></td>
    </tr>
    </table>

    <p><a class='btn btn-dark' href="admin.php">Zu den Kategorien</a></p>
</body>
</html>