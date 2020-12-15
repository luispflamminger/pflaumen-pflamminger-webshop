<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="../style.css" />
        <?php
        session_start();
        require "../funktionen.php";
        authentifizierung($_SESSION["typ"], "kunde");
    
        if (isset($_GET["id"])) {
            $bewID = htmlspecialchars($_GET["id"]);
        } else {
            header("Location: start.php");
            exit;
        }

        $sql = "SELECT * FROM bewertung WHERE id=$bewID";

        $con = db_verbinden();
        $res = mysqli_query($con, $sql);
        $dsatzBew = mysqli_fetch_assoc($res);

        if (is_null($dsatzBew["bezug"])) {
            $istAntwort = false;
        } else {
            $istAntwort = true;
            $hauptbeitragID = $dsatzBew["bezug"];
            $sql = "SELECT titel FROM bewertung WHERE id=$hauptbeitragID";
            $hauptbeitragTitel = mysqli_fetch_assoc(mysqli_query($con, $sql));    
            $hauptbeitragTitel = $hauptbeitragTitel["titel"];
        }

        $sql = "SELECT vorname, name FROM benutzer WHERE id=" . $dsatzBew["benutzer"];
        $dsatzName = mysqli_fetch_assoc(mysqli_query($con, $sql));

        mysqli_close($con);
        
        echo "<title>Pflaumen Pflamminger: " . $dsatzBew["titel"] . "</title>";
        ?>

        
    </head>
    <body>
        <h2>Kundenbewertung</h2>
        <p><?php
        echo "Von: " . $dsatzName["vorname"] . " " . $dsatzName["name"] . "<br>";
        if ($istAntwort == true) {
            echo "Antwort auf: <a href='bewertung.php?id=$hauptbeitragID'>" . $hauptbeitragTitel . "</a>";
        }
        echo "</p>";
        echo "<h4>" . $dsatzBew["titel"] . "</h4>";
        echo "<p>" . $dsatzBew["bewertung"] . " von 5 Pflaumen</p>";
        echo "<p>" . nl2br($dsatzBew["beschreibung"]) . "</p>";
        ?>

        <h3>Antworten</h3>

        <?php

            function antworten_anzeigen($id, $ebene) {
                $con = db_verbinden();
                $sql = "SELECT * FROM bewertung WHERE bezug=$id";
                $res = mysqli_query($con, $sql);
                while ($dsatzAntwort = mysqli_fetch_assoc($res)) {
                    $sql = "SELECT vorname, name FROM benutzer WHERE id=" . $dsatzAntwort["benutzer"];
                    $dsatzName = mysqli_fetch_assoc(mysqli_query($con, $sql));

                    echo "<p style='margin-left: ${ebene}em'>--------------------------------------------------------------</p>";
                    echo "<p style='margin-left: ${ebene}em'>Antwort von " . $dsatzName["vorname"] . " " . $dsatzName["name"] . "</p>";
                    echo "<p style='margin-left: ${ebene}em'>" . $dsatzAntwort["bewertung"] . " von 5 Pflaumen</p>";
                    echo "<h4 style='margin-left: ${ebene}em'>" . $dsatzAntwort["titel"] . "</h4>";
                    echo "<p style='margin-left: ${ebene}em'>" . nl2br($dsatzAntwort["beschreibung"]) . "</p>";
                    echo "<a style='margin-left: ${ebene}em' href='bewertung.php?id=" . $dsatzAntwort["id"] . "'>Antwort anzeigen</a> | <a href='antw_verfassen.php?id=" . $dsatzAntwort["id"] . "'>Antwort verfassen</a>";
                    antworten_anzeigen($dsatzAntwort["id"], $ebene+4);
                }
                $ebeneTemp = $ebene - 4;
                if ($ebeneTemp != 0) {
                    echo "<p style='margin-left: " . $ebeneTemp . "em'>--------------------------------------------------------------</p>";
                }
                mysqli_close($con);

            }
            antworten_anzeigen($dsatzBew["id"], 4);
        ?>
        <p><a href="start.php">Zur Startseite</a></p>
    </body>
</html>