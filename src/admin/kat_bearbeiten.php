<!DOCTYPE html>
<html>
    <head>
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
    <body>
        <h2>Kategorie ändern</h2>
        <p>Geben Sie einen neuen Namen für die Kategorie ein oder löschen Sie diese.</p>
        
        <form action="kat_bearbeiten.php" method="post">
        Kategoriename:<br>
        <input type="text" name="nameNeu" size="20" maxlength="40" value="<?php if (isset($nameNeu)) { echo $nameNeu; } else { echo $nameAlt; }; ?>"/>
        <br>
        <input type="hidden" name="id" value="<?php echo $id ?>" />
        <input type="hidden" name="nameAlt" value="<?php echo $nameAlt ?>" />
        <input type="submit" name="update" value="Aktualisieren" />
        <input type="submit" name="delete" value="Löschen" />
         
        </form>
        <br>
    <?php
    echo $meldung;
    ?>
    <br>
    <a href="admin.php">Zurück</a>
    
    </body>
</html>
