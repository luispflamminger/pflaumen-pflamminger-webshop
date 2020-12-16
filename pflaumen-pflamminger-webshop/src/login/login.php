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

        <title>Pflaumen Pflamminger: Die besten Pflaumen</title>
        
        <?php
        require "../funktionen.php";
        
        if(isset($_POST["email"])) {
            $con = db_verbinden();
            $sql = "SELECT * FROM benutzer WHERE email = '";
            $sql .= htmlspecialchars($_POST["email"]) ."' ";
            $res = mysqli_query($con, $sql);
            $dsatz = mysqli_fetch_assoc($res);
            mysqli_close($con);
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
                    exit;
                } else if ($dsatz["typ"] == "kunde") {
                    session_start();
                    $_SESSION["login"] = "ok";
                    $_SESSION["vorname"] = $dsatz["vorname"];
                    $_SESSION["nachname"] = $dsatz["name"];
                    $_SESSION["email"] = $dsatz["email"];
                    $_SESSION["typ"] = $dsatz["typ"];

                    header("Location: ../shop/start.php?newUser=false");
                    exit;
                } else {
                    $meldung = "Unbekannter Fehler.<br>Bitte versuchen Sie es nochmal!";
                }
            } else {
                $meldung = "Das Passwort ist falsch!";
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
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="email"> E-Mail Adresse</label>
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
                        <label for="password">Passwort</label>
                        <input id="password"
                            class="form-control"
                            type="password"
                            name="passwort"
                            size="20"
                            maxlength="30"
                            value="<?php if (isset($_POST["passwort"])) { echo htmlspecialchars($_POST["passwort"]); } ?>"
                        />
                    </div>
                    <button class="btn btn-primary mb-2" type="submit">Einloggen</button>
                </form>
            </div>
            <div class="pl-3 pt-1 m-1">
                <div class="mb-1">Neu bei Pflaumen Pflamminger?</div>
                <a class="btn btn-outline-secondary mb-2" href="registrieren.php">Registrieren</a>
            </div>
        </div>
    </body>
</html>
