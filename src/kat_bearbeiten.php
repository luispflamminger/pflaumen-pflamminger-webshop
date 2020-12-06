<!DOCTYPE html>
<html>
<head>
    <title>Pflaumen Pflamminger: Kategorie bearbeiten</title>
    <?php
    session_start();
    if(isset($_SESSION["typ"]) && $_SESSION["typ"] == "admin")
    {
        $con = mysqli_connect("","root");
        mysqli_select_db($con,"pflaumenshop");
        if($_GET["id"]=="neu"){

        }
        $sql ="SELECT name FROM kategorie WHERE name='".$_POST["kategorie"]."'";
        $res = mysqli_query($con, $sql);
    ?>
</head>
<body>
    <?php
        echo "Hallo ".$_SESSION["vorname"]."!  (falls du kein Admin bist bitte mach nichts kaputt)";
    ?>
        <form action="kat_bearbeiten.php" method="post">
        Kategoriename:<br>
        <input type="text" name="kategorie" size="20" maxlength="40" />
        <br>
        <input type ="submit" value="Aktualisieren" />
        </form>

    <?php
        $erg = mysqli_fetch_assoc($res);
        
        if($_POST["kategorie"] == $erg["name"])
        {
            echo "Diese Kategorie existiert bereits";
        }
        else
        {
            $sql = "insert kategorie"
            ."(name) values "."('". $_POST["kategorie"] ."')";
            mysqli_query($con, $sql);
            $num = mysqli_affected_rows($con);
            echo "Es wurde $num Kategorie hinzugefügt";
        }


    ?>
    <?php
    }else{
        header("Refresh:3;login.php");
        echo "Zugriff verweigert. Sie werden in 3 Sekunden zum login weitergeleitet";  
    }
    ?>
</body>
</html>
    
</body>
</html>