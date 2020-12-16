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

    <title>Pflaumen Pflamminger: Kategorie hinzufügen</title>
    
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "admin");

    if(isset($_POST["name"]))
    {
        $con = db_verbinden();
        $sql = "SELECT * FROM kategorie";
        $res = mysqli_query($con, $sql);
        
        $name = htmlspecialchars($_POST["name"]);
        //Prüfen ob Name bereits existiert
        $existiert = false;
        while ($dsatz = mysqli_fetch_assoc($res)) {
            if ($dsatz["name"] == $name) {
                $existiert = true;
                break;
            }
        }
        if (!isset($name) || $name == "") {
            //Eingegebener Name ist leer
            $meldung = "Der neue Kategoriename darf nicht leer sein!";
        }
        else if ($existiert == true) {
            $meldung = "Dieser Kategoriename existiert bereits!";
        }
        else {
            //Alles passt
            $sql = "INSERT kategorie (name) values ('$name')";
            $res = mysqli_query($con, $sql);

            if ($res && mysqli_affected_rows($con) == 1) {
                $meldung = "Kategorie erfolgreich hinzugefügt!";
            } else {
                $meldung = "Etwas ist schiefgelaufen...";
            }
        }
        mysqli_close($con);
    }
    else
    {
        $meldung = "";
    }
    
    ?>
</head>
<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Kategorie hinzufügen</h1>
        <p class="lead text-muted">Füge eine neue Kategorie hinzu.</p>
    </div>
    <div class="container py-5" style="max-width: 40em;">
        <?php if ($meldung != "") { echo "<div class='alert alert-danger'>$meldung</div>"; } ?>
        <form action = kat_hinzufuegen.php method="post">
            <div class="form-group">
                <label for="name">Kategoriename:</label>
                <input class="form-control" id="name" type="text" name="name" size="20" maxlength="40">
            </div>
            <input class="btn btn-primary" type="submit" value="Hinzufügen">
        </form>
        <br>
        <a class="btn btn-secondary" href="admin.php">Zurück</a>
</body>
</html>