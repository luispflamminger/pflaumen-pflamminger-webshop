<!DOCTYPE html>
<html>
    <head>
        <title>Pflaumen Pflamminger: Registrierung</title>
        <?php
            if(!isset($_POST["email"])) {

        ?>
    </head>
    <body>
        <form action="registrieren.php" method="post">
            Ihre E-Mail: <br>
            <input type="text" name="email" size="20" maxlength="30" />
            <br>
            Vorname: <br>
            <input type="text" name="vorname" size="20" maxlength="30" />
            <br>
            Nachname: <br>
            <input type="text" name="nachname" size="20" maxlength="30" />
            <br>
            Passwort: <br>
            <input type="password" name="passwort" size="20" maxlength="30" />
            <br>
            Passwort wiederholen: <br>
            <input type="password" name="passwort1" size="20" maxlength="30" />
            <br>
            <input type="submit" value="Jetzt registrieren" />
        </form>
        <?php
                } else {

                    $pattern = "/.+\@[A-Za-z]+\.[A-Za-z]+$/";
                    if ($_POST["passwort"] != $_POST["passwort1"]) {
                        //* Passwörter sind nicht gleich
                    } else if (!preg_match($pattern, $_POST["email"])) {
                        //* E-Mail falsch
                    } else if ($_POST["vorname"] == "" || $_POST["nachname"] == "") {
                        //* Name oder Vorname fehlt
                    } else {
                        //Eingaben sind korrekt
                        $con = mysqli_connect("","root");
                        mysqli_select_db($con,"pflaumenshop");
                        $sql = "INSERT benutzer (typ, email, password, vorname, name)";
                        $sql .= " values ('kunde', '" . $_POST["email"] . "', '";
                        $sql .= $_POST["passwort"] . "', '" . $_POST["vorname"] . "', '";
                        $sql .= $_POST["nachname"] . "');";
                        $res = mysqli_query($con, $sql);
                        
                        if (preg_match("/^Duplicate entry/i", $con["error"])) {
                            //E-Mail existiert bereits
                        }
                        mysqli_close($con);
                    }
                }
        ?>
    </body>
</html>