<?php

    $dbhost = "localhost";
    $dbuser = "blutonium";
    $dbname = "blutopia";
    $dbpass = "train";

    $db = new PDO("pgsql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        $username = $_COOKIE['username'];

        $userdata = $db->query("SELECT * FROM cookieusers WHERE username = '$username'");
        $currentcookies = $userdata->fetch()["cookies"];

        $newcookies = ++$currentcookies;
        
        $res = $db->prepare("UPDATE cookieusers SET cookies=$newcookies WHERE username='$username'");
        $res->execute();

        echo $newcookies;

    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $username = $_COOKIE['username'];

        $userdata = $db->query("SELECT * FROM cookieusers WHERE username = '$username'");
        $currentcookies = $userdata->fetch()["cookies"];

        echo $currentcookies;

    }
?>