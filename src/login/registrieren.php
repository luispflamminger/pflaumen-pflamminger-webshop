<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
                integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
                crossorigin="anonymous">
        <link rel="stylesheet" href="../custom-style.css">
        <title>Pflaumen Pflamminger: Registrierung</title>
        <?php
        require "../funktionen.php";
        
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
                $con = db_verbinden();
                $sql = "INSERT benutzer (typ, email, password, vorname, name)";
                $sql .= " values ('kunde', '";
                $sql .= htmlspecialchars($_POST["email"]) . "', '";
                $sql .= htmlspecialchars($_POST["passwort"]) . "', '";
                $sql .= htmlspecialchars($_POST["vorname"]) . "', '";
                $sql .= htmlspecialchars($_POST["nachname"]) . "');";
                $res = mysqli_query($con, $sql);
                
                if (preg_match("/^Duplicate entry/i", mysqli_error($con))) {
                    //E-Mail existiert bereits
                    $meldung = "Diese E-Mail existiert bereits! Bitte loggen Sie sich <a href='login.php'>hier</a> ein.";
                } else { //TODO: Datenbank auf positive Rückmeldung prüfen ($res wird in dem Fall true, sonst false)
                    //Registrierung erfolgreich
                    session_start();
                    $_SESSION["login"] = "ok";
                    $_SESSION["vorname"] = htmlspecialchars($_POST["vorname"]);
                    $_SESSION["nachname"] = htmlspecialchars($_POST["nachname"]);
                    $_SESSION["email"] = htmlspecialchars($_POST["email"]);
                    $_SESSION["typ"] = "kunde";
                    header("Location: ../shop/start.php?newUser=true");
                    exit;
                }
                mysqli_close($con);                
            }
        }
        ?>
    </head>
    <body class="bg-light">
    <div class="container login-container">
        <div class="row p-3 m-3">
                <img 
                    class="img-fluid"
                        src="../../img/logo_schrift.png"
                        alt="Pflaumen Pflamminger Logo"
                    />
        </div>
        <div class="border rounded-lg p-3 m-1 bg-white">
            <?php
            if (isset($meldung)) {
                echo "<div class='alert alert-danger'>$meldung</div>";
            }       
            ?>
            <form action="registrieren.php" method="post">
                <div class="form-group">
                    <label for="email">E-Mail Adresse</label>
                    <input id="email"
                        class="form-control"
                        type="text"
                        name="email"
                        size="20"
                        maxlength="30"
                        value="<?php if (isset($_POST["email"])) { echo htmlspecialchars($_POST["email"]); } ?>"
                    />
                </div>
                <div class="form-group">
                    <label for="vorname">Vorname</label>
                    <input id="vorname"
                        class="form-control"
                        type="text"
                        name="vorname"
                        size="20"
                        maxlength="30"
                        value="<?php if (isset($_POST["vorname"])) { echo htmlspecialchars($_POST["vorname"]); } ?>"
                    />
                </div>
                <div class="form-group">
                    <label for="nachname">Nachname</label>
                    <input id="nachname"
                        class="form-control"
                        type="text"
                        name="nachname"
                        size="20"
                        maxlength="30"
                        value="<?php if (isset($_POST["nachname"])) { echo htmlspecialchars($_POST["nachname"]); } ?>"
                    />
                </div>
                <div class="form-group">
                    <label for="passwort">Passwort</label>
                    <input id="passwort"
                        class="form-control"
                        type="password"
                        name="passwort"
                        size="20"
                        maxlength="30"
                        value="<?php if (isset($_POST["passwort"])) { echo htmlspecialchars($_POST["passwort"]); } ?>"
                    />
                </div>
                <div class="form-group">
                    <label for="passwort1">Passwort wiederholen</label>
                    <input id="passwort1"
                        class="form-control"
                        type="password"
                        name="passwort1"
                        size="20"
                        maxlength="30"
                        value="<?php if (isset($_POST["passwort1"])) { echo htmlspecialchars($_POST["passwort1"]); } ?>"
                    />
                </div>
                <button class="btn btn-primary mb-2" type="submit">Jetzt registrieren</button>
                <!-- <input type="submit" value="Jetzt registrieren" /> -->
            </form>
        </div>
        <div class="pl-3 pt-1 m-1">
                <div class="mb-1">Bereits registriert?</div>
                <a class="btn btn-outline-secondary mb-2" href="login.php">Zum Login</a>
        </div>
    </div>
    </body>
</html>
