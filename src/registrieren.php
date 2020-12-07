<!DOCTYPE html>
<html>
    <head>
        <title>Pflaumen Pflamminger: Registrierung</title>
        <?php
        if(isset($_POST["email"])) {
            $pattern = "/.+\@[A-Za-z]+\.[A-Za-z]+$/";
            if ($_POST["passwort"] != $_POST["passwort1"]) {
                //Passwörter stimmen nicht überein
                $meldung = "Passwörter stimmen nicht überein!";
            } else if (!preg_match($pattern, $_POST["email"])) {
                //* E-Mail falsch
                $meldung = "Bitte geben Sie eine korrekte E-Mail Adresse an!";
            } else if ($_POST["vorname"] == "" || $_POST["nachname"] == "") {
                //* Name oder Vorname fehlt
                $meldung = "Bitte geben Sie ihren Vor- und Nachnamen an!";
            } else {
                //Eingaben sind korrekt
                $con = mysqli_connect("","root");
                mysqli_select_db($con,"pflaumenshop");
                $sql = "INSERT benutzer (typ, email, password, vorname, name)";
                $sql .= " values ('kunde', '" . $_POST["email"] . "', '";
                $sql .= $_POST["passwort"] . "', '" . $_POST["vorname"] . "', '";
                $sql .= $_POST["nachname"] . "');";
                $res = mysqli_query($con, $sql);
                
                if (preg_match("/^Duplicate entry/i", mysqli_error($con))) {
                    //E-Mail existiert bereits
                    $meldung = "Diese E-Mail existiert bereits! Bitte loggen Sie sich <a href='login.php'>hier</a> ein.";
                    mysqli_close($con);
                } else { //TODO: Datenbank auf positive Rückmeldung prüfen ($res wird in dem Fall true, sonst false)
                    //Registrierung erfolgreich
                    session_start();
                    $_SESSION["login"] = "ok";
                    $_SESSION["vorname"] = $_POST["vorname"];
                    $_SESSION["nachname"] = $_POST["nachname"];
                    $_SESSION["email"] = $_POST["email"];
                    $_SESSION["typ"] = "kunde";
                    header("Location: shop.php?newUser=true");
                    exit;
                }
                
            }
        }
        ?>
    </head>
    <body>
        <form action="registrieren.php" method="post">
            Ihre E-Mail: <br>
            <input type="text" name="email" size="20" maxlength="30" value="<?php if (isset($_POST["email"])) { echo $_POST["email"]; } ?>" />
            <br>
            Vorname: <br>
            <input type="text" name="vorname" size="20" maxlength="30" value="<?php if (isset($_POST["vorname"])) { echo $_POST["vorname"]; } ?>"/>
            <br>
            Nachname: <br>
            <input type="text" name="nachname" size="20" maxlength="30" value="<?php if (isset($_POST["nachname"])) { echo $_POST["nachname"]; } ?>"/>
            <br>
            Passwort: <br>
            <input type="password" name="passwort" size="20" maxlength="30" value="<?php if (isset($_POST["passwort"])) { echo $_POST["passwort"]; } ?>"/>
            <br>
            Passwort wiederholen: <br>
            <input type="password" name="passwort1" size="20" maxlength="30" value="<?php if (isset($_POST["passwort1"])) { echo $_POST["passwort1"]; } ?>"/>
            <br>
            <input type="submit" value="Jetzt registrieren" />
        </form>
        <?php
        if (isset($meldung)) {
            echo "<div>$meldung</div>";
        }        
        ?>
    </body>
</html>
