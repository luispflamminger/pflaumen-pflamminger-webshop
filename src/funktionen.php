<?php
function authentifizierung (&$sessionVar, $berechtigterNutzer) {
    if($sessionVar != "admin" && (!isset($sessionVar) || $sessionVar != $berechtigterNutzer)) {
        header("Refresh:3;../login/login.php");
        echo "Zugriff verweigert. Sie werden in 3 Sekunden zum login weitergeleitet";
        exit;
    }
}

function db_verbinden () {
    $con = mysqli_connect("","root");
    mysqli_select_db($con,"pflaumenshop");
    return $con;
}

?>
