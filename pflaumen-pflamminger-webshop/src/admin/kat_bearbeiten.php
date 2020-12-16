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

    <title>Pflaumen Pflamminger: Kategorie bearbeiten</title>
    
    <?php
    //Authentifizierung des Nutzers
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "admin");

    if (isset($_GET["id"])) {
        //Nutzer kommt von admin.php
        $id = htmlspecialchars($_GET["id"]);
        $nameAlt = htmlspecialchars($_GET["name"]);
        $meldung = "";
    } else {

        $con = db_verbinden();

        if(isset($_POST["update"]))
        {
            //Nutzer hat Kategorie geändert
            $id = htmlspecialchars($_POST["id"]);
            $nameAlt = htmlspecialchars($_POST["nameAlt"]);
            $nameNeu = htmlspecialchars($_POST["nameNeu"]);

            $sql = "SELECT * FROM kategorie";
            $res = mysqli_query($con, $sql);
            
            //Prüfen ob Name bereits existiert
            $existiert = false;
            while ($dsatz = mysqli_fetch_assoc($res)) {
                if ($dsatz["name"] == $nameNeu) {
                    $existiert = true;
                    break;
                }
            }
            if (!isset($nameNeu) || $nameNeu == "") {
                //Eingegebener Name ist leer
                $meldung = "Der neue Kategoriename darf nicht leer sein!";
            } else if ($nameNeu == $nameAlt) {
                //Namen gleichen sich
                $meldung = "Bitte gib einen neuen Namen für die Kategorie ein!";
            } else if ($existiert == true) {
                $meldung = "Dieser Kategoriename existiert bereits!";
            } else {
                //Alles passt
                $sql = "UPDATE kategorie SET name = '" . $nameNeu . "' WHERE id=" . $id . ";";
                $res = mysqli_query($con, $sql);

                if ($res && mysqli_affected_rows($con) == 1) {
                    $meldung = "Name erfolgreich geändert!";
                } else {
                    $meldung = "Etwas ist schiefgelaufen...";
                }
            }
        }
        else
        {
            $sql = "DELETE FROM kategorie WHERE" . " name = '" . htmlspecialchars($_POST["nameAlt"]) . "'";
            $res = mysqli_query($con,$sql);
            $num = mysqli_affected_rows($con);
            if ($res && mysqli_affected_rows($con) == 1) {
                header("Location: admin.php?gelöscht=true");
                exit;
            } else {
                header("Location: admin.php?gelöscht=false");
                exit;
            }
            
        }
        mysqli_close($con);
    }
    echo "<title>Pflaumen Pflamminger: " . $nameAlt . " bearbeiten</title>\n";
    ?>
    

</head>
<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Kategorie ändern</h1>
        <p class="lead text-muted">Gib einen neuen Namen für die Kategorie ein oder lösche diese.</p>
    </div>
    <div class="container py-5" style="max-width: 40em;">
        <?php if ($meldung != "") { echo "<div class='alert alert-danger'>$meldung</div>"; } ?>
    
        <form action="kat_bearbeiten.php" method="post">
            <div class="form-group">
                <label for="name">Kategoriename:</label>
                <input class="form-control" id="name" type="text" name="nameNeu" size="20" maxlength="40" value="<?php if (isset($nameNeu)) { echo $nameNeu; } else { echo $nameAlt; }; ?>"/>
            </div>
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="hidden" name="nameAlt" value="<?php echo $nameAlt ?>" />
            <input class="btn btn-primary" type="submit" name="update" value="Aktualisieren" />
            <input class="btn btn-dark" type="submit" name="delete" value="Löschen" />
        </form>
        <br>
        <a class="btn btn-secondary" href="admin.php">Zurück</a>
    </div>
</body>
</html>
