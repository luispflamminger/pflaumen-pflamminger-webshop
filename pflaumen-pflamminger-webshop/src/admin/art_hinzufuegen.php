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

    <title>Pflaumen Pflamminger: Artikel hinzufügen</title>
    
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
<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Artikel hinzufügen</h1>
        <p class="lead text-muted">Gib hier die Daten des neuen Artikels ein.</p>
    </div>
    <div class="container py-5" style="max-width: 40em;">
        <?php if ($meldung != "") { echo "<div class='alert alert-danger'>$meldung</div>"; } ?>
        <form action = art_hinzufuegen.php method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Artikelname:</label>
                <input class="form-control" id="name" type="text" name="name" size=20 maxlength="40">
            </div>
            <div class="form-group">
                <label for="beschreibung">Beschreibung:</label>
                <textarea class="form-control" id="beschreibung" rows="8" cols="20" name="beschreibung"></textarea>
            </div>
            <div class="form-group">
                <label for="kategorie">Kategorie:</label>
                <select class="form-control" id="kategorie" name="kategorie" >
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
                </select>
            </div>
            <div class="form-group">
                <label for="preis">Preis:</label>
                <input class="form-control" id="preis" type="number" step="0.01" value="5.00" name="preis" >
            </div>
            <div class="form-group">
                <label for="bild">Bild:</label>
                <input class="form-control-file" id="bild" type="file" name="bild">
            </div>
            <input type="hidden" name="katId" value="<?php echo $katId; ?>">
            <input class="btn btn-primary" type="submit" name="hinzufuegen" value="Hinzufügen">
        </form>
        <br>
        <a class="btn btn-secondary" href="artikel.php?katId=<?php echo $katId; ?>">Zurück</a>
        <?php mysqli_close($con); ?>
    </div>
</body>
</html>
