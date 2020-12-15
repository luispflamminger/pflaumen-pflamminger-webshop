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
            $id = htmlspecialchars($_GET["id"]);
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

        <form action="warenkorb.php" method="POST">
            <select name="anzahl" >
                <option value=1 selected>1</option>
                <?php for ($i=2; $i <= 30; $i++) { echo "<option value=$i>$i</option>"; } ?>
            </select>
            <input type="hidden" name="artikelID" value="<?php echo $id; ?>">
            <input type="submit" name="hinzufuegen" value="In den Warenkorb">
        </form>

        <h3>Bewertungen</h3>

        <?php
        while ($dsatzBew = mysqli_fetch_assoc($res)) {
            $sql = "SELECT name, vorname FROM benutzer WHERE id=" . $dsatzBew["benutzer"];
            $dsatzName = mysqli_fetch_assoc(mysqli_query($con, $sql));
        
            echo "<p>--------------------------------------------------------------</p>";
            echo "<p>Bewertung von " . $dsatzName["vorname"] . " " . $dsatzName["name"] . "</p>";
            echo "<p>" . $dsatzBew["bewertung"] . " von 5 Pflaumen</p>";
            echo "<h4>" . $dsatzBew["titel"] . "</h4>";
            echo "<p>" . nl2br($dsatzBew["beschreibung"]) . "</p>";
            echo "<a href='bewertung.php?id=" . $dsatzBew["id"] . "'>Antworten anzeigen</a> | <a href='antw_verfassen.php?id=" . $dsatzBew["id"] . "'>Antwort verfassen</a>";
        }
        echo "<p>--------------------------------------------------------------</p>";
        echo "<a href='bew_verfassen.php?id=" . $dsatzArt["id"] . "'>Bewertung verfassen</a>"; 

        mysqli_close($con);
        ?>
        <p><a href="start.php">Zur Startseite</a></p>
    </body>
</html>