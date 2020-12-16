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

    <title>Pflaumen Pflamminger: Kasse</title>

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
    mysqli_close($con);
    ?>
</head>

<?php 
$activeFile = pathinfo(__FILE__, PATHINFO_FILENAME);
$activeSubdir = substr(pathinfo(__FILE__, PATHINFO_DIRNAME), strrpos(pathinfo(__FILE__, PATHINFO_DIRNAME), "/")+1);
require "../navbar.php"; ?>

<body class="bg-light">
    <div class="container text-center mt-5">
        <h1>Kasse</h1>
        <p class="lead text-muted">Bitte gib zum Abschließen deiner Bestellung deine Liefer- und Zahlungsdaten an.</p>
    </div>
    <div class="container py-5" style="max-width: 40em;">
        <h4 class="mb-3">Lieferadresse</h4>
        <?php if ($meldung != "") { echo "<div class='alert alert-danger'>$meldung</div>"; } ?>
        <form action="kasse.php" method="post">
            <div class="form-row">
                <div class="col">
                    <label for="vorname">Vorname:</label>
                    <input class="form-control" id="vorname" type="text" name="vorname" size="20" maxlength="30" value="<?php echo $_SESSION["vorname"]; ?>">
                </div>
                <div class="col">
                    <label for="nachname">Nachname:</label>
                    <input class="form-control" id="nachname" type="text" name="nachname" size="20" maxlength="30" value="<?php echo $_SESSION["nachname"]; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input class="form-control" id="email" type="text" name="email" size="20" maxlength="30" value="<?php echo $_SESSION["email"]; ?>">
            </div>
            <div class="form-row">
                <div class="col-10">
                    <label for="strasse">Straße:</label>
                    <input class="form-control" id="strasse" type="text" name="strasse" size="20" maxlength="50">
                </div>
                <div class="col-2">
                    <label for="hnr">Hausnr.:</label>
                    <input class="form-control" id="hnr" type="text" name="hnr" size="5" maxlength="10">
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label for="plz">PLZ:</label>
                    <input class="form-control" id="plz" type="text" name="plz" size="5" maxlength="10">
                </div>
                <div class="col-9">
                    <label for="ort">Ort:</label>
                    <input class="form-control" id="ort" type="text" name="ort" size="20" maxlength="30">
                </div>
            </div>
            <div class="form-group">
                <label for="land">Land:</label>
                <input class="form-control" id="land" type="text" name="land" size="20" maxlength="30" value="Deutschland">
            </div>
            <hr class="mb-4">
            <h4 class="mb-3">Zahlung</h4>
            <div class="card mb-2">
                <div class="card-body">Zu zahlen: <span class="font-weight-bold"><?php echo $gesamtpreis; ?> €</span></div>
            </div>
            <div class="form-group">
                <label for="zahlung">Zahlungsmethode:</label>
                <select class="form-control" id="zahlung" name="zahlung">
                    <option value="PayPal">PayPal</option>
                    <option value="Kreditkarte">Kreditkarte</option>
                </select>
            </div>
            <div class="form-group">
                <label for="kartenname">Name des Karteninhabers:</label>
                <input class="form-control" id="kartenname" type="text" name="kartenname" size="20" maxlength="30" value="<?php echo $_SESSION["vorname"] . " " . $_SESSION["nachname"]; ?>">
            </div>
            <div class="form-group">
                <label for="kartennummer">Kreditkartennummer:</label>
                <input class="form-control" id="kartennummer" type="number" name="kartennummer" size="20" maxlength="30">
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="kartendatum">Ablaufdatum:</label>
                    <input class="form-control" id="kartendatum" type="date" name="kartendatum" size="20">
                </div>
                <div class="col">
                    <label for="CVV">CVV:</label>
                    <input class="form-control" id="CVV" type="number" name="CVV" size="3" maxlength="3">
                </div>
            </div>
            <hr class="mb-4">
            <input class="btn btn-primary" style="width: 100%" type="submit" name="gekauft" value="Kauf abschließen">
        </form>
    </div>
</body>
</html>