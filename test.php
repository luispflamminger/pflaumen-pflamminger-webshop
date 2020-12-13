<?php
        $sql = "SELECT * FROM bewertung WHERE id=3";

        $con = mysqli_connect("","root");
        mysqli_select_db($con,"pflaumenshop");
        $res = mysqli_query($con, $sql);
        $dsatzArt = mysqli_fetch_assoc($res);
        var_dump($dsatzArt["bezug"]);
?>