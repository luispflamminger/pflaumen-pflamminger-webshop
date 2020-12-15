<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger: Kategorien</title>
    <link rel="stylesheet" href="../style.css" />
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "admin");

    if(!isset($_POST["hinzufuegen"])) {
        $meldung = "";
    }
    else {
        $con = db_verbinden();
        $sql = "SELECT * FROM artikel";
        $res = mysqli_query($con, $sql);
        
        $name = htmlspecialchars($_POST["name"]);
        $beschreibung = htmlspecialchars($_POST["beschreibung"]);
        $kategorie = htmlspecialchars($_POST["kategorie"]);
        $preis = htmlspecialchars($_POST["preis"]);
        
        //Prüfen ob Name bereits existiert
        $existiert = false;
        while ($dsatz = mysqli_fetch_assoc($res)) {
            if ($dsatz["name"] == $name) {
                $existiert = true;
                break;
            }
        }

        $pattern = "/^[0-9]+,[0-9][0-9]/";
        $pattern2 = "/^[0-9]+.[0-9][0-9]/";
        if (!isset($preis) || $preis == "") {
            $meldung = "Bitte lege einen Preis für den Artikel fest!";
            $preisOk = false;
        } else if (preg_match($pattern2, $preis)) {
            $preisOk = true;
        } else if (preg_match($pattern, $preis)) {
            $preis = str_replace(",", ".", $preis);
            $preisOk = true;
        } else {
            $meldung = "Bitte gib den Preis im Format \"12,34\" an!";
            $preisOk = false;
        }
        


        if (!isset($name) || $name == "") {
            //Eingegebener Name ist leer
            $meldung = "Der neue Artikelname darf nicht leer sein!";
        } else if ($existiert == true) {
            $meldung = "Dieser Artikelname existiert bereits!";
        } else if (!isset($kategorie) || $kategorie == "") {
            $meldung = "Bitte wähle eine Kategorie!";
        } else if ($preisOk == false) {
            $meldung = "Bitte lege einen Preis für den Artikel fest!";
        } else {
            //Alles passt
            
            //****Code für Dateiupload****
            if ($_FILES["bild"]["name"] == "") {
                //Kein Bild hochgeladen
                $uploadMeldung = "Bitte lade ein Bild hoch!";
                $uploadOk = false;
            } else {

                $zielpfad = "../../img/" . basename($_FILES["bild"]["name"]);
                $dateityp = strtolower(pathinfo($zielpfad,PATHINFO_EXTENSION));
                $uploadOk = true;
                $uploadMeldung = "";
                //Ist die Datei ein Bild
                $check = getimagesize($_FILES["bild"]["tmp_name"]);
                if ($check == false && $uploadOk == true) {
                    $uploadOk = false;
                    $meldung = "Datei ist kein Bild!";
                }

                //Datei umbenennen falls sie existiert
                while (file_exists($zielpfad) && $uploadOk == true) {
                    $zielpfad = substr($zielpfad, 0, strrpos($zielpfad, "."));
                    $zielpfad .= "1.$dateityp";
                }

                //Dateigröße prüfen
                if ($_FILES["bild"]["size"] > 500000 && $uploadOk == true) {
                    $uploadOk = false;
                    $uploadMeldung = "Datei ist zu groß";
                }

                //Dateityp prüfen
                if ($dateityp != "jpg" && $dateityp != "jpeg" && $dateityp != "png" && $uploadOk == true) {
                    $uploadOk = false;
                    $uploadMeldung = "Es sind nur Bilder vom Typ JPG oder PNG erlaubt!";
                }

                if ($uploadOk == true) {
                    if (!move_uploaded_file($_FILES["bild"]["tmp_name"], $zielpfad)) {
                        $uploadOk = false;
                        $uploadMeldung = "Es gab einen Fehler beim Upload. Bitte versuche es nochmal.";
                    } else {
                        //Zielpfad für Datenbank fertig machen (../../ entfernen)
                        $zielpfad = substr($zielpfad, 6);
                    }
                }
            }

            if ($uploadOk == false) {
                $meldung = $uploadMeldung;
            } else {

                $sql = "INSERT artikel (name, beschreibung, kategorie, bild, preis) values ('$name', '$beschreibung', $kategorie, '$zielpfad', $preis)";
                $res = mysqli_query($con, $sql);

                if ($res && mysqli_affected_rows($con) == 1) {
                    $meldung = "Artikel erfolgreich hinzugefügt!";
                } else {
                    $meldung = "Etwas ist schiefgelaufen...";
                }
            }
        }
        mysqli_close($con);
    }
    ?>
</head>
<body>
    <h2>Artikel hinzufügen</h2>
    <form action = art_hinzufuegen.php method="post" enctype="multipart/form-data">
        Artikelname: <input type="text" name="name" size=20 maxlength="40"><br>
        Beschreibung: <textarea rows="8" cols="20" name="beschreibung"></textarea><br>
        Kategorie: 
        <select name="kategorie" >
            <?php
            $con = db_verbinden();
            $sql = "SELECT * FROM kategorie";
            $res = mysqli_query($con, $sql);

            if (isset($_GET["katId"])) {
                $katId = htmlspecialchars($_GET["katId"]);
            } else if (isset($_POST["katId"])) {
                $katId = htmlspecialchars($_POST["katId"]);
            } else {
                $katId = 0;
            }
            while ($dsatz = mysqli_fetch_assoc($res)) {
                echo "\t\t\t<option value='" . $dsatz["id"] . "' ";
                if ($dsatz["id"] = $katId) { echo "selected "; }
                echo ">" . $dsatz["name"] . "</option>\n";
            }
            ?>
        </select><br>
        Preis: <input type="number" step="0.01" value="5.00" name="preis" ><br>
        Bild: <input type="file" name="bild">
        <input type="hidden" name="katId" value="<?php echo $katId; ?>">
        <input type="submit" name="hinzufuegen" value="Hinzufügen">
    </form>
    <br>
    <a href="artikel.php?katId=<?php echo $katId; ?>">Zurück</a>
    <?php
    echo "<p>$meldung</p>";
    mysqli_close($con);
    ?>
</body>
</html>
