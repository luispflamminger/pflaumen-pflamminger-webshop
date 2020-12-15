<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger: Kategorien</title>
    <link rel="stylesheet" href="../style.css" />
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "admin");

    //ID des Artikels festlegen
    if (isset($_POST["id"]) && $_POST["id"] != "") {
        $id = $_POST["id"];
    } else if (isset($_GET["id"]) && $_GET["id"] != "") {
        $id = $_GET["id"];
    } else {
        //Kein zu bearbeitender Artikel definiert
        header("Location: artikel.php");
    }

    if (!isset($_POST["aendern"])) {
        $meldung = "";
    }
    else {
        $con = db_verbinden();
        $sql = "SELECT * FROM artikel";
        $res = mysqli_query($con, $sql);
        
        $name = $_POST["name"];
        $beschreibung = $_POST["beschreibung"];
        $kategorie = $_POST["kategorie"];
        $preis = $_POST["preis"];
        
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
                $keinBild = true;
            } else {

                $zielpfad = "../../img/" . basename($_FILES["bild"]["name"]);
                $dateityp = strtolower(pathinfo($zielpfad,PATHINFO_EXTENSION));
                $uploadOk = true;
                $keinBild = false;
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

            if ($keinBild == false && $uploadOk == false) {
                $meldung = $uploadMeldung;
            } else {

                if ($keinBild) {
                    $sql = "UPDATE artikel SET name='$name', beschreibung='$beschreibung', kategorie=$kategorie, preis=$preis WHERE id=$id";
                } else {
                    $sql = "UPDATE artikel SET name='$name', beschreibung='$beschreibung', kategorie=$kategorie, bild='$bild', preis=$preis WHERE id=$id";
                }
                
                $res = mysqli_query($con, $sql);

                if ($res && mysqli_affected_rows($con) == 1) {
                    $meldung = "Artikel erfolgreich geändert!";
                } else {
                    $meldung = "Etwas ist schiefgelaufen...";
                }
            }
        }
    }
    ?>
</head>
<body>
    <h2>Artikel ändern</h2>
    <?php
    // Artikelinformationen holen
    $con = db_verbinden();
    $sql = "SELECT * FROM artikel WHERE id=$id";
    $res = mysqli_query($con, $sql);
    $dsatzArtikel = mysqli_fetch_assoc($res);
    ?>

    <form action = art_bearbeiten.php method="post" enctype="multipart/form-data">
        Artikelname: <input type="text" name="name" size=20 maxlength="40" value="<?php echo htmlspecialchars($dsatzArtikel["name"]); ?>"><br>
        Beschreibung: <textarea rows="8" cols="20" name="beschreibung"><?php echo htmlspecialchars($dsatzArtikel["beschreibung"]); ?></textarea><br>
        Kategorie: 
        <select name="kategorie" >
            <?php
            $sql = "SELECT * FROM kategorie";
            $res = mysqli_query($con, $sql);

            while ($dsatzKat = mysqli_fetch_assoc($res)) {
                echo "\t\t\t<option value='" . $dsatzKat["id"] . "' ";
                if ($dsatzKat["id"] = $dsatzArtikel["kategorie"]) { echo "selected "; }
                echo ">" . $dsatzKat["name"] . "</option>\n";
            }
            ?>
        </select><br>
        Preis: <input type="number" step="0.01" name="preis" value="<?php echo htmlspecialchars($dsatzArtikel["preis"]); ?>"><br>
        Bild: <input type="file" name="bild"> <br> (Falls Sie kein Bild hochladen, bleibt das bisherige Bild erhalten.)
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="aendern" value="Ändern">
    </form>
    <br>
    <a href="artikel.php?katId=<?php echo $dsatzArtikel["kategorie"]; ?>">Zurück</a>
    <?php
    echo "<p>$meldung</p>";
    ?>
</body>
</html>
