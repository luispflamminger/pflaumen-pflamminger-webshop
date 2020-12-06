<!DOCTYPE html>
<html>
    <head>
        <title>Pflaumen Pflamminger: Kategorie bearbeiten</title>
        <?php
        //Authentifizierung des Nutzers
        session_start();
        if(isset($_SESSION["typ"]) && $_SESSION["typ"] == "admin")
        {
            
            $con = mysqli_connect("","root");
            mysqli_select_db($con,"pflaumenshop");
            $sql = "SELECT name FROM kategorie WHERE id=" . $_GET["id"];
            $name = mysqli_query($con, $_GET)

            if (isset($_POST["kategorie"])) {
                //Änderung bereits abgeschickt

            } 
            
            /*
            else {
                //Kommt von admin Seite
                echo "</head>\n<body>";
                if ($_GET["id"] == "neu") {
                    //Hinzufügen
                    echo "<p>Geben Sie den Namen der Kategorie ein, die Sie hinzufügen möchten.";

                } else {
                    //Ändern
                    $sql = "SELECT name FROM kategorie WHERE id = " . $_GET["id"];
                    $dsatz = mysqli_fetch_assoc(mysqli_query($con, $sql));
                    echo "<p>Geben Sie einen neuen Namen für die Kategorie " . $dsatz["name"] . " ein oder löschen Sie diese.";
                }
            }
            */
        ?>
    </head>
    <body>
        <p>Geben Sie einen neuen Namen für die Kategorie ein oder löschen Sie diese.</p>
        <form action="kat_bearbeiten.php" method="post">
        Kategoriename:<br>
        <input type="text" name="kategorie" size="20" maxlength="40" />
        <br>
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
        <input type="submit" value="Aktualisieren" />
        
        </form>
        <br>
    <?php
        echo $meldung;

        $erg = mysqli_fetch_assoc($res);
        
        if($_POST["kategorie"] == $erg["name"])
        {
            echo "Diese Kategorie existiert bereits";
        }
        else
        {

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
