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

    <title>Pflaumen Pflamminger: Artikel bearbeiten</title>
    <link rel="stylesheet" href="../style.css" />
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "admin");

    //ID des Artikels festlegen
    if (isset($_POST["id"]) && $_POST["id"] != "") {
        $id = htmlspecialchars($_POST["id"]);
    } else if (isset($_GET["id"]) && $_GET["id"] != "") {
        $id = htmlspecialchars($_GET["id"]);
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
        
        $name = htmlspecialchars($_POST["name"]);
        $beschreibung = htmlspecialchars($_POST["beschreibung"]);
        $kategorie = htmlspecialchars($_POST["kategorie"]);
        $preis = htmlspecialchars($_POST["preis"]);
        
        //Prüfen ob Name bereits existiert
        $existiert = false;
        if ($_POST["nameAlt"] != $_POST["name"]) {
            while ($dsatz = mysqli_fetch_assoc($res)) {
                if ($dsatz["name"] == $name) {
                    $existiert = true;
                    break;
                }
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
                    $sql = "UPDATE artikel SET name='$name', beschreibung='$beschreibung', kategorie=$kategorie, bild='$zielpfad', preis=$preis WHERE id=$id";
                }
                
                $res = mysqli_query($con, $sql);

                if ($res && mysqli_affected_rows($con) == 1) {
                    $meldung = "Artikel erfolgreich geändert!";
                } else {
                    $meldung = "Etwas ist schiefgelaufen...";
                }
            }
        }
        mysqli_close($con);
    }
    ?>
</head>
<body class="bg-light">

    <div class="container text-center mt-5">
        <h1>Artikel bearbeiten</h1>
        <p class="lead text-muted">Gib hier die neuen Daten des Artikels ein.</p>
    </div>

    <?php
    // Artikelinformationen holen
    $con = db_verbinden();
    $sql = "SELECT * FROM artikel WHERE id=$id";
    $res = mysqli_query($con, $sql);
    $dsatzArtikel = mysqli_fetch_assoc($res);
    ?>
    <div class="container py-5" style="max-width: 40em;">
        <?php if ($meldung != "") { echo "<div class='alert alert-danger'>$meldung</div>"; } ?>
        <form action = art_bearbeiten.php method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Artikelname:</label>
                <input class="form-control" type="text" id="name" name="name" size=20 maxlength="40" value="<?php echo $dsatzArtikel["name"]; ?>">
            </div>
            <div class="form-group">
                <label for="beschreibung">Beschreibung:</label>
                <textarea class="form-control" id="beschreibung" rows="8" cols="20" name="beschreibung"><?php echo $dsatzArtikel["beschreibung"]; ?></textarea>
            </div>
            <div class="form-group">
                <label for="kategorie">Kategorie:</label>
                <select class="form-control" id="kategorie" name="kategorie" >
                <?php
                $sql = "SELECT * FROM kategorie";
                $res = mysqli_query($con, $sql);

                while ($dsatzKat = mysqli_fetch_assoc($res)) {
                    echo "\t\t\t<option value='" . $dsatzKat["id"] . "' ";
                    if ($dsatzKat["id"] == $dsatzArtikel["kategorie"]) { echo "selected "; }
                    echo ">" . $dsatzKat["name"] . "</option>\n";
                }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="preis">Preis:</label>
                <input class="form-control" id="preis" type="number" step="0.01" name="preis" value="<?php echo $dsatzArtikel["preis"]; ?>">
            </div>
            <div class="form-group">
                <label for="bild">Bild:</label>
                <input class="form-control-file" id="bild" type="file" name="bild"><br>(Falls Sie kein Bild hochladen, bleibt das bisherige Bild erhalten.)
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="nameAlt" value="<?php echo $dsatzArtikel["name"]; ?>">
            <input class="btn btn-primary" type="submit" name="aendern" value="Ändern">
        </form>
        <br>
        <a class="btn btn-secondary" href="artikel.php?katId=<?php echo $dsatzArtikel["kategorie"]; ?>">Zurück</a>
        <?php mysqli_close($con); ?>
    </div>
</body>
</html>
