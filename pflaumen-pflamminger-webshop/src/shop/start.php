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

    $con = db_verbinden();
    $sql ="SELECT * FROM kategorie";
    $res = mysqli_query($con, $sql);
    ?>
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>
<body class="bg-light">
    <div class="container text-center mt-5">
        <p class="lead text-muted">
            <?php
            echo "Willkommen bei Pflaumen Pflamminger, " . $_SESSION["vorname"] . "!";
            ?>
        </p>
        <h1>Kategorien</h1>
        <p class="lead text-muted">Wähle eine Kategorie aus, um durch die verschiedenen Angebote zu stöbern.</p>
    </div>

    <div class="container py-5" style="max-width: 30em;">
        <!-- <table class="table table-striped">
            <tr>
                <th>Kategoriename</th>
            </tr> -->
        <?php
        while ($dsatz = mysqli_fetch_assoc($res)) { 
            echo "<div class='card mb-1'>";
            echo "<a href='kategorie.php?katId=" . $dsatz["id"] . "&katName=" . $dsatz["name"] . "'><div class='card-body text-center'>".$dsatz["name"]."</div></a>";
            echo "</div>";
        }

        mysqli_close($con);
        ?>
        <!-- </table> -->
    </div>
</body>
</html>