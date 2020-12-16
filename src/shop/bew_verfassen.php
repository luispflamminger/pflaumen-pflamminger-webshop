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
    
    <title>Pflaumen Pflamminger: Bewertung verfassen</title>

    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    if (isset($_GET["id"])) {
        $artId = htmlspecialchars($_GET["id"]);
    }

    if(!isset($_POST["verfassen"])) {
        $meldung = "";
    }
    else {

        if(!isset($_POST["titel"]) || $_POST["titel"] == "") {
            $meldung = "Bitte einen Titel eingeben!";
        } else if (!isset($_POST["beschreibung"]) || $_POST["beschreibung"] == "") {
            $meldung = "Bitte eine Beschreibung eingeben!";
        } else if (!isset($_POST["artId"])) {
            $meldung = "Ein Fehler ist aufgetreten...";
        } else {
            
            $artId = htmlspecialchars($_POST["artId"]);

            $con = db_verbinden();
            $sql = "SELECT id FROM benutzer WHERE email = '" . $_SESSION["email"] . "'";
            $res = mysqli_query($con, $sql);
            $dsatz = mysqli_fetch_assoc($res);

            $sql = "INSERT bewertung (titel, beschreibung, bewertung, benutzer, artikel) VALUES ('" . htmlspecialchars($_POST["titel"])
                  . "', '" . htmlspecialchars($_POST["beschreibung"]) . "', " . htmlspecialchars($_POST["bewertung"]) . ", " . $dsatz["id"] . ", " . $artId . ");";

            $res = mysqli_query($con, $sql);

            if ($res && mysqli_affected_rows($con) == 1) {
                $id = mysqli_insert_id($con);
                
                header("Location: bewertung.php?id=$id");
            } else {
                $meldung = "Etwas ist schiefgelaufen...";
            }
            mysqli_close($con);
        }
        

    }
    ?>
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>

<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Bewertung verfassen</h1>
    </div>
    <div class="container py-5" style="max-width: 40em;">
        <?php if ($meldung != "") { echo "<div class='alert alert-danger'>$meldung</div>"; } ?>
        <form action = bew_verfassen.php method="post">
            <div class="form-group">
                <label for="titel">Titel:</label>
                <input class="form-control" id="titel" type="text" name="titel" size=20 maxlength="50">
            </div>
            <div class="form-group">
                <label for="beschreibung">Beschreibung:</label>
                <textarea class="form-control" id="beschreibung" rows="8" cols="50" name="beschreibung"></textarea>
            </div>
            <div class="form-group">
                <label >Bewertung:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="bewertung" id="radio1" value="1">
                    <label class="form-check-label" for="radio1">1</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="bewertung" id="radio2" value="2">
                    <label class="form-check-label" for="radio2">2</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="bewertung" id="radio3" value="3">
                    <label class="form-check-label" for="radio3">3</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="bewertung" id="radio4" value="4">
                    <label class="form-check-label" for="radio4">4</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="bewertung" id="radio5" value="5" checked>
                    <label class="form-check-label" for="radio5">5</label>
                </div>
            </div>
            <input type="hidden" name="artId" value="<?php echo $artId; ?>">
            <input class="btn btn-primary" type="submit" name="verfassen" value="Verfassen">
        </form>
        <br>
        <a class="btn btn-secondary" href="artikel.php?id=<?php echo $artId; ?>">Zurück</a>

</body>
</html>
