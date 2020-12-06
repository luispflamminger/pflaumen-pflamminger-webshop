<!DOCTYPE html>
<html>
<head>
    <title> "Pflaumen Pflamminger: Kategorien"</title>
    <?php
    session_start();
    if($_SESSION["typ"] == "admin")
    {
        $con = mysqli_connect("","root");
        mysqli_select_db($con,"pflaumenshop");
        $sql ="SELECT * FROM kategorie";
        $res = mysqli_query($con, $sql);
    ?>
</head>
<body>
    <?php
        echo "Hallo ".$_SESSION["vorname"]."!  (falls du kein Admin bist bitte mach nichts kaputt)";
    ?>
        <table border = "1">
        <tr>
        <th>Kategoriename</th><th></th>
        </tr>
        <?php
            echo mysqli_num_rows($res);
            while ($dsatz = mysqli_fetch_assoc($res)) {
               echo "<tr> <td> ".$dsatz["name"]." </td>";
               echo "<td> <a href='kat_bearbeiten.php?id=".$dsatz["id"].">Bearbeiten</a> </td>";
               echo "</tr>";
            }
        ?>
        <tr>
            <td><a href = "kat_hinzufügen.php?id=neu">Neue Kategorie hinzufügen</a></td><td></td>
        </tr>
        </table>
        <?php
    }else{
        echo "Zugriff verweigert. Sie werden in 3 Sekunden zum login weitergeleitet";
        sleep(3);
        header("Location: login.php");
    }
    ?>
    
</body>
</html>