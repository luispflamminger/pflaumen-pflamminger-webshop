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
        $dsatz = mysqli_fetch_assoc($res);

        echo "<title>Pflaumen Pflamminger: " . $dsatz["name"] . "</title>";
        ?>

        
    </head>
    <body>
        <h2><?php echo $dsatz["name"]; ?></h2>
        <img src='../../<?php echo $dsatz["bild"]; ?>' width='100' height='100' />
        <p><?php echo $dsatz["beschreibung"]; ?></p>

        <form action="artikel.php" method="POST">
            <select name="anzahl" >
                <option value=1 selected>1</option>
                <?php for ($i=2; $i <= 30; $i++) { echo "<option value=$i>$i</option>"; } ?>
            </select>
            <input type="submit" name="hinzufuegen" value="In den Einkaufswagen">
        </form>

        <h3>Bewertungen</h3>
        <p><a href="start.php">Zur Startseite</a></p>
    </body>
</html>