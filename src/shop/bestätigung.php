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

    <title>Pflaumen Pflamminger: Bestellung erfolgreich!</title>

    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    if (!isset($_GET["meldung"])) {
        header("Location: warenkorb.php");
        exit;
    }

    ?>
</head>
<body class="bg-light">

    <div class="container text-center mt-5">
        <h1>Bestellung abgeschlossen!</h1>
        <p class="lead text-muted">
            <?php
            if ($_GET["meldung"] == "paypal") {
                echo "Sie werden in Kürze zu PayPal weitergeleitet!";
                header("Refresh:7;start.php");
                exit;
            } else if ($_GET["meldung"] == "kreditkarte") {
                echo "Vielen Dank für Ihren Kauf.<br>Sie werden in Kürze zur Startseite weitergeleitet.";
                header("Refresh:7;start.php");
                exit;
            }
            ?>
        </p>
    </div>
</body>
</html>