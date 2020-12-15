<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger: Kategorien</title>
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
<body>
    <h2>Kategorie hinzufügen</h2>
    <form action = kat_hinzufuegen.php method="post">
        Kategoriename:
        <input type="text" name="name" size=20 maxlength="40">
        <br>
        <input type="submit" value="Hinzufügen">
    </form>
    <br>
    <a href="admin.php">Zurück</a>
    <?php
    echo "<p>$meldung</p>";
    ?>
</body>
</html>