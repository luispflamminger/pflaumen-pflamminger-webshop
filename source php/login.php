<!DOCTYPE html>
<html>
    <head>
        <title>Pflaumen Pflamminger: Die besten Pflaumen</title>
        <?php
        if(!isset($_POST["email"])){
        ?>
    </head>
    <body>
        <form action="login.php" method="post">
        Ihre E-Mail: <br>
        <input type="text" name="email" size="20" maxlength="30" />
        <br>
        Passwort: <br>
        <input type="password" name="passwort" size="20" maxlength="30" />
        <br>
        <input type="submit" value="Einloggen"/>
        </form>
        <p> Neu bei Pflaumen Pflamminger?<br> Dann hier <a href="registrieren.php"> registrieren</a> </p>
    </body>
    <?php
        } else{
            $con = mysqli_connect("","root");
            mysqli_select_db($con,"pflaumenshop");
            $sql = "select typ from benutzer where email = '".$_POST["email"]."' ";
            $res = mysqli_query($con, $sql);
            $dsatz = mysqli_fetch_assoc($res);
            if($dsatz["typ"] == "admin")
            {
                header("Location: admin.php");
                exit;
            }
            else if ($dsatz["typ"] == "kunde")
            {
                header("Location: shop.php");
                exit;
            }
            else
            {
                header("Location: kein_account.php");
                exit;
            }
        }
    ?>
</html>