<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger: Kategorien</title>
    <link rel="stylesheet" href="../style.css" />
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    if (isset($_GET["id"])) {
        $artId = $_GET["id"];
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
            
            $artId = $_POST["artId"];

            $con = db_verbinden();
            $sql = "SELECT id FROM benutzer WHERE email = '" . $_SESSION["email"] . "'";
            $res = mysqli_query($con, $sql);
            $dsatz = mysqli_fetch_assoc($res);

            $sql = "INSERT bewertung (titel, beschreibung, bewertung, benutzer, artikel) VALUES ('" . $_POST["titel"]
                  . "', '" . $_POST["beschreibung"] . "', " . $_POST["bewertung"] . ", " . $dsatz["id"] . ", " . $artId . ");";

            $res = mysqli_query($con, $sql);

            if ($res && mysqli_affected_rows($con) == 1) {
                $id = mysqli_insert_id($con);
                
                header("Location: bewertung.php?id=$id");
            } else {
                $meldung = "Etwas ist schiefgelaufen...";
            }
        }
        

    }
    ?>
</head>
<body>
    <h2>Bewertung verfassen</h2>
    <form action = bew_verfassen.php method="post">
        Titel: <input type="text" name="titel" size=20 maxlength="30"><br>
        Beschreibung: <textarea rows="8" cols="50" name="beschreibung"></textarea><br>
        Bewertung: 
        1 <input type="radio" name="bewertung" value="1" />
        2 <input type="radio" name="bewertung" value="2" />
        3 <input type="radio" name="bewertung" value="3" />
        4 <input type="radio" name="bewertung" value="4" />
        5 <input type="radio" name="bewertung" value="5" checked />
        <input type="hidden" name="artId" value="<?php echo $artId; ?>">
        <input type="submit" name="verfassen" value="Verfassen">
    </form>
    <br>
    <a href="artikel.php?id=<?php echo $artId; ?>">Zurück</a>
    <?php
    echo "<p>$meldung</p>";
    ?>
</body>
</html>
