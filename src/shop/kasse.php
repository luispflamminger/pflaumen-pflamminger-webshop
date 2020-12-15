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

    <title>Pflaumen Pflamminger</title>

    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    if(isset($_SESSION["gesamtpreis"])) {
        $gesamtpreis = $_SESSION["gesamtpreis"];
    } else {
        header("Location: warenkorb.php");
        exit;
    }

    //Prüfen ob alles richtig eingegeben wurde
    if (isset($_POST["gekauft"])) {
        if ($_POST["vorname"] == "" || $_POST["nachname"] == "" || $_POST["email"] == "" || $_POST["strasse"] == "" || $_POST["hnr"] == "" || $_POST["plz"] == "" || $_POST["ort"] == "" || $_POST["land"] == "") {
            $meldung = "Bitte füllen Sie alle Adressfelder aus!";
        } else if ($_POST["zahlung"] == "Kreditkarte" && ($_POST["kartenname"] == "" || $_POST["kartennummer"] == "" || $_POST["kartendatum"] == "" || $_POST["CVV"] == "")) {
            $meldung = "Bitte gültige Kreditkartendaten hinterlegen, oder eine andere Zahlungsmethode wählen";
        } else if ($_POST["zahlung"] == "PayPal") {
            header("Location: bestätigung.php?meldung=paypal");
            exit;
        } else {
            header("Location: bestätigung.php?meldung=kreditkarte");
            exit;
        }
    } else {
        $meldung = "";
    }

    $con = db_verbinden();
    $sql = "SELECT * FROM benutzer WHERE email='" . $_SESSION["email"] . "'";
    $dsatzNutzer = mysqli_fetch_assoc(mysqli_query($con, $sql));
    ?>
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>

<body>
    <h2>Kasse</h2>
    <p>Zu zahlen: <?php echo $gesamtpreis; ?> €</p>
    <div>
        <form action="kasse.php" method="post">
            <p style="color: red"><?php echo $meldung; ?></p>
            <h4>Lieferadresse:</h4>
            Vorname: <input type="text" name="vorname" size="20" maxlength="30" value="<?php echo $_SESSION["vorname"]; ?>"><br>
            Nachname: <input type="text" name="nachname" size="20" maxlength="30" value="<?php echo $_SESSION["nachname"]; ?>"><br>
            E-Mail: <input type="text" name="email" size="20" maxlength="30" value="<?php echo $_SESSION["email"]; ?>"><br>
            Straße: <input type="text" name="strasse" size="20" maxlength="50"><br>
            Hausnr.: <input type="text" name="hnr" size="5" maxlength="10"><br> 
            PLZ: <input type="text" name="plz" size="5" maxlength="10"><br>
            Ort: <input type="text" name="ort" size="20" maxlength="30"><br>
            Land: <input type="text" name="land" size="20" maxlength="30" value="Deutschland"><br>
            
            <h4>Zahlungsmethode:</h4>
            <select name="zahlung">
                <option value="PayPal">PayPal</option>
                <option value="Kreditkarte">Kreditkarte</option>
            </select>
            Name auf der Karte:<input type="text" name="kartenname" size="20" maxlength="30" value="<?php echo $_SESSION["vorname"] . " " . $_SESSION["nachname"]; ?>"><br>
            Kreditkartennummer:<input type="number" name="kartennummer" size="20" maxlength="30"><br>
            Ablaufdatum: <input type="date" name="kartendatum" size="20"><br>
            CVV: <input type="number" name="CVV" size="3" maxlength="3"><br>
            <input type="submit" name="gekauft" value="Kauf abschließen">
        </form>

    </div>
</body>
</html>