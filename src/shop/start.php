<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger</title>
    <link rel="stylesheet" href="../style.css" />
    <?php
    session_start();
    require "../funktionen.php";
    authentifizierung($_SESSION["typ"], "kunde");

    $con = db_verbinden();
    $sql ="SELECT * FROM kategorie";
    $res = mysqli_query($con, $sql);
    ?>
</head>
<body>
    <?php

    echo "<h2>Willkommen bei Pflaumen Pflamminger, ".$_SESSION["vorname"]."!</h2>";      
    ?>

    <div>
        <p>Wähle eine Kategorie:</p>
        <table border = "1">
            <tr>
                <th>Kategoriename</th>
            </tr>
        <?php
        while ($dsatz = mysqli_fetch_assoc($res)) { 
            echo "<tr>";
            echo "<td><a href='kategorie.php?katId=" . $dsatz["id"] . "&katName=" . $dsatz["name"] . "'>".$dsatz["name"]."</a></td>";
            echo "</tr>";
        }
        ?>
        </table>

    </div>
    <div>
        <br>
        <a href = "warenkorb.php">Zum Warenkorb</a>
    </div>
</body>
</html>