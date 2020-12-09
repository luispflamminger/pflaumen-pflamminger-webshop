<!DOCTYPE html>
<html>
    <head>
        <title>Pflaumen Pflamminger: Die besten Pflaumen</title>
        <?php
        require "../funktionen.php";
        
        if(isset($_POST["email"])) {
            $con = db_verbinden();
            $sql = "SELECT * FROM benutzer WHERE email = '".$_POST["email"]."' ";
            $res = mysqli_query($con, $sql);
            $dsatz = mysqli_fetch_assoc($res);
            if (mysqli_num_rows($res) == 0) {
                $meldung = "Diese E-Mail existiert nicht. Bitte registrieren Sie sich <a href='registrieren.php'>hier</a>.";
            } else if($_POST["passwort"] == $dsatz["password"]) {
                //E-Mail und Passwort korrekt
                if($dsatz["typ"] == "admin") {
                    session_start();
                    $_SESSION["login"] = "ok";
                    $_SESSION["vorname"] = $dsatz["vorname"];
                    $_SESSION["nachname"] = $dsatz["name"];
                    $_SESSION["email"] = $dsatz["email"];
                    $_SESSION["typ"] = $dsatz["typ"];
                    header("Location: ../admin/admin.php");
                    mysqli_close($con);
                    exit;
                } else if ($dsatz["typ"] == "kunde") {
                    session_start();
                    $_SESSION["login"] = "ok";
                    $_SESSION["vorname"] = $dsatz["vorname"];
                    $_SESSION["nachname"] = $dsatz["nachname"];
                    $_SESSION["email"] = $dsatz["email"];
                    $_SESSION["typ"] = $dsatz["typ"];
                    header("Location: ../shop/start.php?newUser=false");
                    mysqli_close($con);
                    exit;
                } else {
                    $meldung = "Unbekannter Fehler.<br>Bitte versuchen Sie es nochmal!";
                    mysqli_close($con);
                }
            } else {
                $meldung = "Das Passwort ist falsch!";
                mysqli_close($con);
            }
        }
        ?>
    </head>
    <body>
        <form action="login.php" method="post">
        Ihre E-Mail: <br>
        <input type="text" name="email" size="20" maxlength="30" value="<?php if (isset($_POST["email"])) { echo $_POST["email"]; } ?>" />
        <br>
        Passwort: <br>
        <input type="password" name="passwort" size="20" maxlength="30" value="<?php if (isset($_POST["passwort"])) { echo $_POST["passwort"]; } ?>" />
        <br>
        <input type="submit" value="Einloggen"/>
        </form>
        <p> Neu bei Pflaumen Pflamminger?<br> Dann hier <a href="registrieren.php"> registrieren</a> </p>
        <?php
        if (isset($meldung)) {
            echo "<div>$meldung</div>";
        }       
        ?>
    </body>
</html>
