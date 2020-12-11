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
            $id = $_GET["id"];
        } else {
            header("Location: start.php");
            exit;
        }

        $sql = "SELECT * FROM artikel WHERE id=$id";

        $con = db_verbinden();
        $res = mysqli_query($con, $sql);
        $dsatzArt = mysqli_fetch_assoc($res);

        $sql = "SELECT * FROM bewertung WHERE artikel=$id";

        $res = mysqli_query($con, $sql);

        echo "<title>Pflaumen Pflamminger: " . $dsatzArt["name"] . "</title>";
        ?>

        
    </head>
    <body>
        <h2><?php echo $dsatzArt["name"]; ?></h2>
        <img src='../../<?php echo $dsatzArt["bild"]; ?>' width='100' height='100' />
        <p><?php echo $dsatzArt["beschreibung"]; ?></p>

        <form action="artikel.php" method="POST">
            <select name="anzahl" >
                <option value=1 selected>1</option>
                <?php for ($i=2; $i <= 30; $i++) { echo "<option value=$i>$i</option>"; } ?>
            </select>
            <input type="submit" name="hinzufuegen" value="In den Einkaufswagen">
        </form>

        <h3>Bewertungen</h3>

        <?php
        while ($dsatzBew = mysqli_fetch_assoc($res)) {
            $sql = "SELECT name FROM benutzer WHERE id=" . $dsatzBew["benutzer"];
            $vornameBew = mysqli_fetch_assoc(mysqli_query($con, $sql));
            echo "<p>--------------------------------------------------------------</p>";
            echo "<p>Bewertung von " . $vornameBew["name"] . "</p>";
            echo "<p>" . $dsatzBew["bewertung"] . " von 5 Pflaumen</p>";
            echo "<h4>" . $dsatzBew["titel"] . "</h4>";
            echo "<p>" . $dsatzBew["beschreibung"] . "</p>";
            echo "<a href='bewertung.php?id=" . $dsatzBew["id"] . "'>Antworten anzeigen</a> | <a href='antw_verfassen.php?" . $dsatzBew["id"] . "'>Antwort verfassen</a>";
        }
        echo "<p>--------------------------------------------------------------</p>";
        echo "<a href='bew_verfassen.php?id=" . $dsatzArt["id"] . "'>Bewertung verfassen</a>"; 
        ?>
        <p><a href="start.php">Zur Startseite</a></p>
    </body>
</html>