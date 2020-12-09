<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="../style.css" />
        <?php
        session_start();
        require "../funktionen.php";
        authentifizierung($_SESSION["typ"], "kunde");
    
        if (isset($_GET["katName"])) {
            $katName = $_GET["katName"];
        } else {
            $katName = "";
        }

        if (isset($_GET["katId"]) && $_GET["katId"] != "") {
            $sql ="SELECT * FROM artikel WHERE kategorie = " . $_GET["katId"];
        } else {
            $sql ="SELECT * FROM artikel";
        }

        $con = db_verbinden();
        $res = mysqli_query($con, $sql);

        echo "<title>Pflaumen Pflamminger: $katName</title>";
        ?>

        
    </head>
    <body>
        <h2><?php echo $katName; ?></h2>
        <table border="1">
            <tr>
                <th><!--Bild--></th>
                <th>Artikelname</th>
                <th>Beschreibung</th>
                <th>Preis</th>
            </tr>
            <?php
            while ($dsatz = mysqli_fetch_assoc($res)) { 
                echo "";
                echo "<tr>";
                echo "<td><img src='../../" . $dsatz["bild"] . "' width='100' height='100' /></td>";
                echo "<td><a href='artikel.php?id=".$dsatz["id"]."&name=".$dsatz["name"]."'>" . $dsatz["name"] . "</a></td>";
                echo "<td>" . $dsatz["beschreibung"] . "</td>";
                echo "<td>" . number_format($dsatz["preis"], 2, ',', '.') . "&nbsp;&euro;</td>";
                echo "</tr>";
            }
            ?>

        </table>

        <p><a href="start.php">Zur Startseite</a></p>
    </body>
</html>