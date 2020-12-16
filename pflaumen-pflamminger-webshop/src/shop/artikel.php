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
    authentifizierung($_SESSION["typ"], "kunde");

    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
    } else {
        header("Location: start.php");
        exit;
    }

    $sql = "SELECT * FROM artikel WHERE id=$id";

    $con = db_verbinden();
    $res = mysqli_query($con, $sql);
    $dsatzArt = mysqli_fetch_assoc($res);

    $sql = "SELECT * FROM bewertung WHERE artikel=$id";

    $res = mysqli_query($con, $sql);

    echo "<title>Pflaumen Pflamminger: " . $dsatzArt["name"] . "</title>";
    ?>

    
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>

<body class="bg-light">
    <div class="container py-5" style="max-width: 60em;">
        <div class="row">
            <div class="col-md-4">
                <img class="img-fluid img-thumbnail" src='../../<?php echo $dsatzArt["bild"]; ?>' />
            </div>
            <div class="col-md-8">
                <h2 class="mb-3"><?php echo $dsatzArt["name"]; ?></h2>
                <p><?php echo $dsatzArt["beschreibung"]; ?></p>

                <form action="warenkorb.php" method="POST">
                    <div class="form-group">
                        <select class="custom-select" name="anzahl" >
                            <option value=1 selected>1</option>
                            <?php for ($i=2; $i <= 30; $i++) { echo "<option value=$i>$i</option>"; } ?>
                        </select>
                    </div>
                    
                    <input type="hidden" name="artikelID" value="<?php echo $id; ?>">
                    <input class="btn btn-primary" type="submit" name="hinzufuegen" value="In den Warenkorb">
                </form>

            </div>
        </div>
        <hr class="mb-4">


        <h4 class="mb-4">Bewertungen</h4>

        <?php
        while ($dsatzBew = mysqli_fetch_assoc($res)) {
            $sql = "SELECT name, vorname FROM benutzer WHERE id=" . $dsatzBew["benutzer"];
            $dsatzName = mysqli_fetch_assoc(mysqli_query($con, $sql));
            echo "<div class='card mb-3'>";
            echo "<div class='card-header'>Bewertung von " . $dsatzName["vorname"] . " " . $dsatzName["name"];
            echo "<br>" . $dsatzBew["bewertung"] . " von 5 Pflaumen</div>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . $dsatzBew["titel"] . "</h5>";
            echo "<p class='card-text'>" . nl2br($dsatzBew["beschreibung"]) . "</p>";
            echo "<a class='card-link' href='bewertung.php?id=" . $dsatzBew["id"] . "'>Antworten anzeigen</a>";
            echo "<a class='card-link' href='antw_verfassen.php?id=" . $dsatzBew["id"] . "'>Antwort verfassen</a>";
            echo "</div></div>";
            // echo "<p>"
            // echo "<p>--------------------------------------------------------------</p>";
            // echo "<p>Bewertung von " . $dsatzName["vorname"] . " " . $dsatzName["name"] . "</p>";
            // echo "<p>" . $dsatzBew["bewertung"] . " von 5 Pflaumen</p>";
            // echo "<h4>" . $dsatzBew["titel"] . "</h4>";
            // echo "<p>" . nl2br($dsatzBew["beschreibung"]) . "</p>";
            // echo "<a href='bewertung.php?id=" . $dsatzBew["id"] . "'>Antworten anzeigen</a> | <a href='antw_verfassen.php?id=" . $dsatzBew["id"] . "'>Antwort verfassen</a>";
        }
        // echo "<p>--------------------------------------------------------------</p>";
        echo "<a class='btn btn-primary' href='bew_verfassen.php?id=" . $dsatzArt["id"] . "'>Bewertung verfassen</a>"; 

        mysqli_close($con);
        ?>
    </div>
</body>
</html>